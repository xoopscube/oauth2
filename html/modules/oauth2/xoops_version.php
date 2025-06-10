<?php
/**
 * OAuth2 module for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

// Module metadata
$modversion['name']        = 'oauth2';
$modversion['dirname']     = 'oauth2';
$modversion['version']     = '1.00.0';
$modversion['description'] = 'oauth2 for XOOPSCube';
$modversion['author']      = 'Nuno Luciano (gigamaster)';
$modversion['credits']     = 'The XOOPSCube Project';
$modversion['license']     = 'GPL 2.0';
$modversion['help']        = '';
$modversion['official']    = 0;
$modversion['image']       = 'images/module_image.svg';
$modversion['icon']        = 'images/module_icon.svg';
$modversion['cube_style']  = true;
$modversion['read_any']    = true;

// SQL file
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
  '{prefix}_oauth2_user_map',
];

//config
$modversion['hasconfig'] = 1;

// Admin settings
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Dashboard';
$modversion['adminmenu'] = 'admin/menu.php';

//config
$modversion['hasconfig'] = 1;

// Admin settings
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Dashboard';
$modversion['adminmenu'] = 'admin/menu.php';

// hasmain
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

// Templates
$modversion['templates'][1]['file'] = 'oauth2_index.html';
$modversion['templates'][1]['description'] = 'OAuth2 Login Provider Selection Page';

// Blocks
$modversion['blocks'][1]['file']        = 'oauth2_login_block.php';
$modversion['blocks'][1]['name']        = _MI_OAUTH2_BLOCK_LOGIN_NAME;
$modversion['blocks'][1]['description'] = _MI_OAUTH2_BLOCK_LOGIN_DESC;
$modversion['blocks'][1]['show_func']   = 'b_oauth2_login_block_show';
$modversion['blocks'][1]['template']    = 'oauth2_login_block.html';
$modversion['blocks'][1]['visible']     = 'show';
$modversion['blocks'][1]['can_clone']   = false;

$modversion['config'][] = [
    'name'        => 'oauth2_admin_password',
    'title'       => '_MI_OAUTH2_ADMIN_PASSWORD',
    'description' => '_MI_OAUTH2_ADMIN_PASSWORD_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => bin2hex(random_bytes(8)), // Generates a 16-character hex string
];
$modversion['config'][] = [
    'name'        => 'oauth2_tech_contact_email',
    'title'       => '_MI_OAUTH2_TECH_CONTACT_EMAIL',
    'description' => '_MI_OAUTH2_TECH_CONTACT_EMAIL_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => $GLOBALS['xoopsConfig']['adminmail'] ?? 'admin@example.com',
];
$modversion['config'][] = [
    'name'        => 'oauth2_identifier_attribute',
    'title'       => '_MI_OAUTH2_IDENTIFIER_ATTR',
    'description' => '_MI_OAUTH2_IDENTIFIER_ATTR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uid' // Or 'mail', 'eduPersonPrincipalName', etc.
];
$modversion['config'][] = [
    'name'        => 'oauth2_uname_attribute',
    'title'       => '_MI_OAUTH2_UNAME_ATTR',
    'description' => '_MI_OAUTH2_UNAME_ATTR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uid' // Or a specific attribute for username
];
$modversion['config'][] = [
    'name'        => 'oauth2_email_attribute',
    'title'       => '_MI_OAUTH2_EMAIL_ATTR',
    'description' => '_MI_OAUTH2_EMAIL_ATTR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'mail'
];
$modversion['config'][] = [
    'name'        => 'oauth2_email_fallback_lookup',
    'title'       => '_MI_OAUTH2_EMAIL_FALLBACK_LOOKUP',
    'description' => '_MI_OAUTH2_EMAIL_FALLBACK_LOOKUP_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0 // Default to No for security/explicitness
];
$modversion['config'][] = [
    'name'        => 'oauth2_name_attribute',
    'title'       => '_MI_OAUTH2_NAME_ATTR',
    'description' => '_MI_OAUTH2_NAME_ATTR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'cn' // Common Name or displayName
];
$modversion['config'][] = [
    'name'        => 'allow_register',
    'title'       => '_MI_OAUTH2_ALLOW_REGISTER',
    'description' => '_MI_OAUTH2_ALLOW_REGISTER_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1 // 1 for yes, 0 for no
];
$modversion['config'][] = [
    'name'        => 'oauth2_default_group',
    'title'       => '_MI_OAUTH2_DEFAULT_GROUP',
    'description' => '_MI_OAUTH2_DEFAULT_GROUP_DESC',
    'formtype'    => 'group', // XOOPS group selector
    'valuetype'   => 'int',
    'default'     => XOOPS_GROUP_USERS
];
$modversion['config'][] = [
    'name'        => 'oauth2_login_redirect_url',
    'title'       => '_MI_OAUTH2_LOGIN_REDIRECT_URL',
    'description' => '_MI_OAUTH2_LOGIN_REDIRECT_URL_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_URL . '/'
];
// Apple Configuration
$modversion['config'][] = [
    'name'        => 'enable_apple_auth',
    'title'       => '_MI_OAUTH2_ENABLE_APPLE_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_APPLE_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'apple_client_id',
    'title'       => '_MI_OAUTH2_APPLE_CLIENT_ID',
    'description' => '_MI_OAUTH2_APPLE_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'apple_team_id',
    'title'       => '_MI_OAUTH2_APPLE_TEAM_ID',
    'description' => '_MI_OAUTH2_APPLE_TEAM_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'apple_key_file_id',
    'title'       => '_MI_OAUTH2_APPLE_KEY_FILE_ID',
    'description' => '_MI_OAUTH2_APPLE_KEY_FILE_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'apple_key_file_path',
    'title'       => '_MI_OAUTH2_APPLE_KEY_FILE_PATH',
    'description' => '_MI_OAUTH2_APPLE_KEY_FILE_PATH_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '', // e.g., XOOPS_TRUST_PATH . '/uploads/apple_authkey.p8'
    'options'     => ['size' => 60],
];
$modversion['config'][] = [
    'name'        => 'apple_redirect_uri',
    'title'       => '_MI_OAUTH2_APPLE_REDIRECT_URI',
    'description' => '_MI_OAUTH2_APPLE_REDIRECT_URI_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_URL . '/modules/oauth2/apple_callback.php', // Example, adjust as needed
    'options'     => ['size' => 60],
];

// Google
$modversion['config'][] = [
    'name'        => 'enable_google_oidc',
    'title'       => '_MI_OAUTH2_ENABLE_GOOGLE_OIDC',
    'description' => '_MI_OAUTH2_ENABLE_GOOGLE_OIDC_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'google_oidc_client_id',
    'title'       => '_MI_OAUTH2_GOOGLE_OIDC_CLIENT_ID',
    'description' => '_MI_OAUTH2_GOOGLE_OIDC_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'google_oidc_client_secret',
    'title'       => '_MI_OAUTH2_GOOGLE_OIDC_CLIENT_SECRET',
    'description' => '_MI_OAUTH2_GOOGLE_OIDC_CLIENT_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];

// Facebook Configuration
$modversion['config'][] = [
    'name'        => 'enable_facebook_auth',
    'title'       => '_MI_OAUTH2_ENABLE_FACEBOOK_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_FACEBOOK_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'facebook_app_id',
    'title'       => '_MI_OAUTH2_FACEBOOK_APP_ID',
    'description' => '_MI_OAUTH2_FACEBOOK_APP_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'facebook_app_secret',
    'title'       => '_MI_OAUTH2_FACEBOOK_APP_SECRET',
    'description' => '_MI_OAUTH2_FACEBOOK_APP_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];

// Instagram Configuration
$modversion['config'][] = [
    'name'        => 'enable_instagram_auth',
    'title'       => '_MI_OAUTH2_ENABLE_INSTAGRAM_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_INSTAGRAM_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'instagram_client_id',
    'title'       => '_MI_OAUTH2_INSTAGRAM_CLIENT_ID',
    'description' => '_MI_OAUTH2_INSTAGRAM_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'instagram_client_secret',
    'title'       => '_MI_OAUTH2_INSTAGRAM_CLIENT_SECRET',
    'description' => '_MI_OAUTH2_INSTAGRAM_CLIENT_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];

// Twitter Configuration
$modversion['config'][] = [
    'name'        => 'enable_twitter_auth',
    'title'       => '_MI_OAUTH2_ENABLE_TWITTER_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_TWITTER_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'twitter_api_key',
    'title'       => '_MI_OAUTH2_TWITTER_API_KEY',
    'description' => '_MI_OAUTH2_TWITTER_API_KEY_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'twitter_api_secret',
    'title'       => '_MI_OAUTH2_TWITTER_API_SECRET',
    'description' => '_MI_OAUTH2_TWITTER_API_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
// Microsoft Account Configuration
$modversion['config'][] = [
    'name'        => 'enable_microsoft_auth',
    'title'       => '_MI_OAUTH2_ENABLE_MICROSOFT_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_MICROSOFT_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'microsoft_client_id',
    'title'       => '_MI_OAUTH2_MICROSOFT_CLIENT_ID',
    'description' => '_MI_OAUTH2_MICROSOFT_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'microsoft_client_secret',
    'title'       => '_MI_OAUTH2_MICROSOFT_CLIENT_SECRET',
    'description' => '_MI_OAUTH2_MICROSOFT_CLIENT_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
// GitHub Configuration
$modversion['config'][] = [
    'name'        => 'enable_github_auth',
    'title'       => '_MI_OAUTH2_ENABLE_GITHUB_AUTH',
    'description' => '_MI_OAUTH2_ENABLE_GITHUB_AUTH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'github_client_id',
    'title'       => '_MI_OAUTH2_GITHUB_CLIENT_ID',
    'description' => '_MI_OAUTH2_GITHUB_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'github_client_secret',
    'title'       => '_MI_OAUTH2_GITHUB_CLIENT_SECRET',
    'description' => '_MI_OAUTH2_GITHUB_CLIENT_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
// Generic OpenID Configuration
$modversion['config'][] = [
    'name'        => 'enable_generic_oidc',
    'title'       => '_MI_OAUTH2_ENABLE_GENERIC_OIDC',
    'description' => '_MI_OAUTH2_ENABLE_GENERIC_OIDC_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'generic_oidc_display_name',
    'title'       => '_MI_OAUTH2_GENERIC_OIDC_DISPLAY_NAME',
    'description' => '_MI_OAUTH2_GENERIC_OIDC_DISPLAY_NAME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'OpenID Connect',
];
$modversion['config'][] = [
    'name'        => 'generic_oidc_issuer_url',
    'title'       => '_MI_OAUTH2_GENERIC_OIDC_ISSUER_URL',
    'description' => '_MI_OAUTH2_GENERIC_OIDC_ISSUER_URL_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => ['size' => 60],
];
$modversion['config'][] = [
    'name'        => 'generic_oidc_client_id',
    'title'       => '_MI_OAUTH2_GENERIC_OIDC_CLIENT_ID',
    'description' => '_MI_OAUTH2_GENERIC_OIDC_CLIENT_ID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'generic_oidc_client_secret',
    'title'       => '_MI_OAUTH2_GENERIC_OIDC_CLIENT_SECRET',
    'description' => '_MI_OAUTH2_GENERIC_OIDC_CLIENT_SECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
$modversion['config'][] = [
    'name'        => 'generic_oidc_scope',
    'title'       => '_MI_OAUTH2_GENERIC_OIDC_SCOPE',
    'description' => '_MI_OAUTH2_GENERIC_OIDC_SCOPE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'openid email profile',
];