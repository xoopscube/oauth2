<?php
/**
 * OAuth2 Logout for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../mainfile.php';

global $xoopsUser, $xoopsConfig;

// Terminate XOOPSCube session
if (is_object($xoopsUser)) {
    // Use logout mechanisms
    $online_handler = xoops_gethandler('online');
    if (is_object($online_handler)) {
        $online_handler->destroy($xoopsUser->getVar('uid'));
    }
    
    // Destroy session
    xoops_session_destroy();

    // Clear the "Remember Me" cookie if it exists
    $user_cookie = $xoopsConfig['usercookie'] ?? 'xoops_user';
    if (isset($_COOKIE[$user_cookie])) {
        setcookie($user_cookie, '', time() - 3600, '/', XOOPS_COOKIE_DOMAIN, 0);
    }
} else {
    // If no user object, still try to destroy any lingering PHP session
    // fallback if session wasn't properly active
    if (session_status() !== PHP_SESSION_NONE) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}

// Clear any OAuth2-specific session data (if stored any than main user session)
if (isset($_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
}
if (isset($_SESSION['oauth2provider'])) {
    unset($_SESSION['oauth2provider']);
}
// if (isset($_SESSION['oauth2_access_token'])) { // If stored the access token
//     unset($_SESSION['oauth2_access_token']);
// }


 // Redirect to homepage
$redirectUrl = XOOPS_URL . '/';
header('Location: ' . $redirectUrl);
exit;
