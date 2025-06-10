<?php
/**
 * OAuth2 Module Admin Index
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

require_once __DIR__ . '/../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/include/cp_header.php';

global $xoopsModule, $xoopsUser, $xoopsConfig;

if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    exit();
}

xoops_cp_header();

// module's name for title
echo '<h2>' . htmlspecialchars($xoopsModule->getVar('name'), ENT_QUOTES) . ' - ' . _AM_OAUTH2_ADMIN_DASHBOARD . '</h2>';


// Default to Dashboard
$action = $_GET['action'] ?? 'Dashboard'; // 

switch (ucfirst(strtolower($action))) {
    case 'Dashboard':
        echo '<h3>' . _AM_OAUTH2_ADMIN_DASHBOARD . '</h3>';
        echo '<p>' . _AM_OAUTH2_DASHBOARD_INTRO . '</p>';
        
        // Link to module index
        $mod_url = XOOPS_URL . '/modules/oauth2/index.php';
        echo '<p><a href="' . htmlspecialchars($mod_url, ENT_QUOTES) . '" class="button">' . _AM_OAUTH2_GOTO_MODULE_URL . '</a></p>';

        // TODO dashboard information here,
        // statistics, quick links to documentation, or status checks.
        
        // List enabled providers (optional)
        // This requires fetching $xoopsModuleConfig, which might not be loaded by default in admin.
        // if need to load it:
        // $module_handler = xoops_gethandler('module');
        // $config_handler = xoops_gethandler('config');
        // $currentModule = $module_handler->getByDirname($xoopsModule->getVar('dirname'));
        // $moduleConfig = $config_handler->getConfigsByCat(0, $currentModule->getVar('mid'));
        // Then check $moduleConfig['enable_google_oidc'], etc.

        break;

    // other admin actions here in the future
    // case 'Logs':
    //     echo "<h3>OAuth2 Logs</h3>";
    //     // Code to display logs or link to log management
    //     break;

    default:
        echo "<p>Unknown action: " . htmlspecialchars($action, ENT_QUOTES) . "</p>";
        redirect_header('index.php?action=Dashboard', 2); // Redirect to dashboard for unknown actions
        break;
}

xoops_cp_footer();
