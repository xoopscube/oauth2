<?php
/**
 * OAuth2 Module Admin Menu
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

$adminmenu[] = [
    'title' => _MI_OAUTH2_ADMENU_DASHBOARD,
    'link'  => 'admin/index.php?action=Dashboard',
];

// admin menu items 
// e.g.,
// $adminmenu[] = [
//     'title' => 'User Mappings', 
//     'link'  => 'admin/index.php?action=UserMappings',
// ];
