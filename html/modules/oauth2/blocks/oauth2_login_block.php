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

function b_oauth2_login_block_show()
{
    global $xoopsUser;

    $block = [];
    if ($xoopsUser) { // only if not logged in
        return false; 
    }

    $module_handler = xoops_gethandler('module');
    $oauth2_module_obj = $module_handler->getByDirname('oauth2');
    
    if (!is_object($oauth2_module_obj) || !$oauth2_module_obj->getVar('isactive')) {
        return false; // OAuth2 module not active
    } 

    $config_handler = xoops_gethandler('config');

    $oauth2ModuleConfig = $config_handler->getConfigsByCat(0, $oauth2_module_obj->getVar('mid'));

    require_once XOOPS_ROOT_PATH . '/modules/oauth2/class/OAuthHelper.class.php';

    $available_providers = OAuthHelper::getAvailableProviders($oauth2ModuleConfig, $oauth2_module_obj->getVar('dirname'));

    $block['oauth_message'] = null; 

    if (empty($available_providers)) {
        $block['oauth_message'] = _MB_OAUTH2_BLOCK_NO_PROVIDERS;
    } else {
        $block['oauth_providers'] = $available_providers;
        $block['oauth_block_title'] = _MB_OAUTH2_BLOCK_LOGIN_PROVIDER;
    }
    
    return $block;
}
