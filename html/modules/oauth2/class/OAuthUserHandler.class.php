<?php
/**
 * OAuth2 User Handler for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

// callback.php already includes mainfile.php
// require_once dirname(__DIR__, 2) . '/mainfile.php'; 

class OAuthUserHandler
{
    private $db;
    private $table_map;
    private $xoopsUserHandler;
    private $xoopsMemberHandler;
    private $moduleConfig;

    public function __construct()
    {
        global $xoopsDB, $xoopsModuleConfig;
        $this->db = $xoopsDB;
        $this->table_map = $this->db->prefix('oauth2_user_map');
        $this->xoopsUserHandler = xoops_gethandler('user');
        $this->xoopsMemberHandler = xoops_gethandler('member');
        $this->moduleConfig = $xoopsModuleConfig; 
    }

    /**
     * Find user ID based on the external ID and provider.
     *
     * @param string $externalId
     * @param string $providerName
     * @return int|false UID or false if not found.
     */
    public function findXoopsUidByExternalId(string $externalId, string $providerName): int|false
    {
        $sql = sprintf(
            "SELECT xoops_uid FROM %s WHERE external_id = %s AND provider = %s",
            $this->table_map,
            $this->db->quoteString($externalId),
            $this->db->quoteString($providerName)
        );
        $result = $this->db->query($sql);
        if ($result && $this->db->getRowsNum($result) > 0) {
            $row = $this->db->fetchArray($result);
            return (int)$row['xoops_uid'];
        }
        return false;
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return XoopsUser|null
     */
    public function findUserByEmail(string $email): ?XoopsUser
    {
        if (empty($email) || !($this->moduleConfig['oauth2_email_fallback_lookup'] ?? 0)) {
            return null;
        }
        $criteria = new CriteriaCompo(new Criteria('email', $email));
        $users = $this->xoopsUserHandler->getObjects($criteria);
        return ($users && count($users) > 0) ? $users[0] : null;
    }

    /**
     * Registers a new user based on OAuth attributes.
     *
     * @param array  $attributes   OAuth provider (e.g., ['name' => 'John Doe', 'email' => 'john@example.com'])
     * @param string $externalId   The user's ID from the OAuth provider.
     * @param string $providerName The name of the OAuth provider.
     * @return XoopsUser|null The newly created XoopsUser object or null on failure.
     */
    public function registerNewUser(array $attributes, string $externalId, string $providerName): ?XoopsUser
    {
        if (!($this->moduleConfig['allow_register'] ?? 0)) {
            return null;
        }

        $newUser = $this->xoopsUserHandler->createUser();

        // Map attributes - this needs to be flexible based on provider data
        // and module config for attribute names
        $unameAttr = $this->moduleConfig['oauth2_uname_attribute'] ?? 'name';
        $emailAttr = $this->moduleConfig['oauth2_email_attribute'] ?? 'email';
        $nameAttr  = $this->moduleConfig['oauth2_name_attribute'] ?? 'name';

        $username = $attributes[$unameAttr] ?? $attributes['name'] ?? $attributes['login'] ?? null;
        if (empty($username)) { // generate unique username
            $username = strtolower($providerName) . '_' . $externalId;
        }
        // check username is unique
        $i = 0;
        $originalUsername = $username;
        while ($this->xoopsUserHandler->getUserCount(new Criteria('uname', $username)) > 0) {
            $i++;
            $username = $originalUsername . $i;
        }
        $newUser->setVar('uname', $username);

        $email = $attributes[$emailAttr] ?? null;
        if ($email) {
            // check email is unique
            if ($this->xoopsUserHandler->getUserCount(new Criteria('email', $email)) > 0) {
                // Handle email conflict: maybe display message to inform the user or admin.
                error_log("OAuth2 Registration: Email '{$email}' already exists. Not setting for new user '{$username}'.");
                $email = ''; // generate email placeholder
            }
            $newUser->setVar('email', $email);
        }
        
        $newUser->setVar('name', $attributes[$nameAttr] ?? $username);
        $newUser->setVar('pass', md5(uniqid(microtime(), true))); // Random password
        $newUser->setVar('level', 1);
        $newUser->setVar('user_regdate', time());
        $newUser->setVar('timezone_offset', $GLOBALS['xoopsConfig']['default_TZ'] ?? 0);
        // Add other required fields with default values

        if (!$this->xoopsUserHandler->insert($newUser)) {
            error_log("OAuth2 Registration: Failed to insert new XOOPS user. Errors: " . implode(', ', $newUser->getErrors()));
            return null;
        }

        // Add to default group
        $defaultGroupId = $this->moduleConfig['oauth2_default_group'] ?? XOOPS_GROUP_USERS;
        if (!$this->xoopsMemberHandler->addUserToGroup($defaultGroupId, $newUser->getVar('uid'))) {
            error_log("OAuth2 Registration: Failed to add user " . $newUser->getVar('uid') . " to group " . $defaultGroupId);
            // Non-fatal, user is created.
        }
        
        // Create mapping
        $this->createMapping($newUser->getVar('uid'), $externalId, $providerName);

        return $newUser;
    }

    /**
     * Creates a mapping between UID and external ID.
     *
     * @param int    $xoopsUid
     * @param string $externalId
     * @param string $providerName
     * @return bool Success or failure.
     */
    public function createMapping(int $xoopsUid, string $externalId, string $providerName): bool
    {
        $sql = sprintf(
            "INSERT INTO %s (xoops_uid, external_id, provider, linked_on) VALUES (%u, %s, %s, %u)",
            $this->table_map,
            $xoopsUid,
            $this->db->quoteString($externalId),
            $this->db->quoteString($providerName),
            time()
        );
        return (bool)$this->db->queryF($sql);
    }

    /**
     * Logs in the specified user.
     *
     * @param XoopsUser $user
     * @return bool True on success, false on failure.
     */
    public function loginXoopsUser(XoopsUser $user): bool
    {
        global $xoopsUser, $session_handler; // session handler

        if (!is_object($user) || $user->getVar('level') == 0) {
            return false;
        }

        $xoopsUser = $user; // Set the global $xoopsUser object

        // Regenerate session ID for security
        if (is_object($session_handler) && method_exists($session_handler, 'regenerate_id')) {
             $session_handler->regenerate_id(true); // true to delete old session
        } elseif (function_exists('xoops_session_regenerate')) { // XCL specific?
            xoops_session_regenerate();
        } else {
            session_regenerate_id(true);
        }
        
        $_SESSION['xoopsUserId'] = $user->getVar('uid');
        $_SESSION['xoopsUserGroups'] = $user->getGroups();
        $_SESSION['logged_in_via_oauth2'] = true; // Flag for logout logic

        // Update last login
        $this->xoopsUserHandler->updateLastLogin($user->getVar('uid'));

        return true;
    }
}
