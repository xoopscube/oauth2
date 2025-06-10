<?php
/**
 * OAuth2 Module Index - Provider Selection Page
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/oauth2/class/OAuthHelper.class.php'; 

global $xoopsModuleConfig, $xoopsConfig, $xoopsTpl, $xoopsUser, $xoopsModule;

// If user is already logged in, redirect to homepage or user account page
// if (is_object($xoopsUser)) {
//     header('Location: ' . XOOPS_URL . '/');
//     exit;
// }

$xoopsOption['template_main'] = 'oauth2_index.html';
require XOOPS_ROOT_PATH . '/header.php';

$module_dirname = $xoopsModule->getVar('dirname');
$available_providers = OAuthHelper::getAvailableProviders($xoopsModuleConfig, $module_dirname);

$xoopsTpl->assign('oauth_message', null); 

if (empty($available_providers)) {
    $xoopsTpl->assign('oauth_message', _MD_OAUTH2_NO_PROVIDERS);
} else {
    $xoopsTpl->assign('oauth_providers', $available_providers);
    $xoopsTpl->assign('oauth_title', _MD_OAUTH2_TITLE);
}

// Assign module name for breadcrumbs or page title
if (is_object($xoopsModule)) {
    $xoopsTpl->assign('xoops_modulename', $xoopsModule->getVar('name'));
}


require XOOPS_ROOT_PATH . '/footer.php';
