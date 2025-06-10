<?php

// Module Info
define('_MI_OAUTH2_NAME', 'OAuth2 Login');
define('_MI_OAUTH2_DESC', 'Provides OAuth2 login capabilities for XOOPSCube');

// Admin Menu
define('_MI_OAUTH2_ADMENU_DASHBOARD', 'Dashboard');

// Block Names
define('_MI_OAUTH2_BLOCK_LOGIN_NAME', 'OAuth2 Login');
define('_MI_OAUTH2_BLOCK_LOGIN_DESC', 'Displays buttons for logging in with configured OAuth2 providers.');

// Configuration items
define('_MI_OAUTH2_ADMIN_PASSWORD', 'OAUTH2 Admin Password');
define('_MI_OAUTH2_ADMIN_PASSWORD_DESC', 'Set the password for accessing the SimpleOAUTH2php admin interface.<br /> The module auto-generates a 16-character hex string.');
define('_MI_OAUTH2_TECH_CONTACT_EMAIL', 'Technical contact emai');
define('_MI_OAUTH2_TECH_CONTACT_EMAIL_DESC', 'Set the technical contact email for SimpleOAUTH2php');

define('_MI_OAUTH2_IDENTIFIER_ATTR', 'OAUTH2 Identifier Attribute');
define('_MI_OAUTH2_IDENTIFIER_ATTR_DESC', 'The OAUTH2 attribute name to use as the primary unique identifier for the user (e.g., uid, eduPersonPrincipalName, mail).');

define('_MI_OAUTH2_UNAME_ATTR', 'XCL Username OAUTH2 Attribute');
define('_MI_OAUTH2_UNAME_ATTR_DESC', 'The OAUTH2 attribute to map to the XCL username (uname). Must result in a unique value.');

define('_MI_OAUTH2_EMAIL_ATTR', 'Email OAUTH2 Attribute');
define('_MI_OAUTH2_EMAIL_ATTR_DESC', 'The OAUTH2 attribute containing the user\'s email address.');

define('_MI_OAUTH2_EMAIL_FALLBACK_LOOKUP', 'Enable Email Fallback Lookup');
define('_MI_OAUTH2_EMAIL_FALLBACK_LOOKUP_DESC', 'If a user is not found by OAUTH2 Identifier, try to find an existing user by matching the OAUTH2 email attribute. If found, the OAUTH2 identifier will be linked to this existing user.');

define('_MI_OAUTH2_NAME_ATTR', 'Real Name OAUTH2 Attribute');
define('_MI_OAUTH2_NAME_ATTR_DESC', 'The OAUTH2 attribute for the user\'s real name (e.g., cn, displayName).');

define('_MI_OAUTH2_ALLOW_REGISTER', 'Allow New User Registration');
define('_MI_OAUTH2_ALLOW_REGISTER_DESC', 'If enabled, new XCL users will be created if they authenticate via OAUTH2 but do not yet have an account.');

define('_MI_OAUTH2_DEFAULT_GROUP', 'Default Group for New Users');
define('_MI_OAUTH2_DEFAULT_GROUP_DESC', 'Select the default XCL group to assign to newly registered OAUTH2 users.');

define('_MI_OAUTH2_LOGIN_REDIRECT_URL', 'Login Redirect URL');
define('_MI_OAUTH2_LOGIN_REDIRECT_URL_DESC', 'The URL to redirect users to after successful OAUTH2 login. Defaults to homepage.');

// Apple
define('_MI_OAUTH2_ENABLE_APPLE_AUTH', 'Enable Sign in with Apple');
define('_MI_OAUTH2_ENABLE_APPLE_AUTH_DESC', 'Allow users to log in using their Apple ID.');
define('_MI_OAUTH2_APPLE_CLIENT_ID', 'Apple Client ID (Services ID)');
define('_MI_OAUTH2_APPLE_CLIENT_ID_DESC', 'Enter the Services ID from your Apple Developer account (e.g., com.example.webapp).');
define('_MI_OAUTH2_APPLE_TEAM_ID', 'Apple Team ID');
define('_MI_OAUTH2_APPLE_TEAM_ID_DESC', 'Enter your Apple Developer Team ID.');
define('_MI_OAUTH2_APPLE_KEY_FILE_ID', 'Apple Key File ID');
define('_MI_OAUTH2_APPLE_KEY_FILE_ID_DESC', 'Enter the Key ID for your private key (.p8 file).');
define('_MI_OAUTH2_APPLE_KEY_FILE_PATH', 'Apple Private Key File Path');
define('_MI_OAUTH2_APPLE_KEY_FILE_PATH_DESC', 'Absolute path to your .p8 private key file (e.g., /path/to/AuthKey_XXXXXXXXXX.p8). Ensure this file is NOT web-accessible.');
define('_MI_OAUTH2_APPLE_REDIRECT_URI', 'Apple Redirect URI');
define('_MI_OAUTH2_APPLE_REDIRECT_URI_DESC', 'The redirect URI registered with Apple. This is typically your module\'s callback URL.');

// Google
define('_MI_OAUTH2_ENABLE_GOOGLE_OIDC', 'Enable Google OIDC Login');
define('_MI_OAUTH2_ENABLE_GOOGLE_OIDC_DESC', 'Allow users to log in using their Google accounts via OIDC.');
define('_MI_OAUTH2_GOOGLE_OIDC_CLIENT_ID', 'Google OIDC Client ID');
define('_MI_OAUTH2_GOOGLE_OIDC_CLIENT_ID_DESC', 'Enter the Client ID obtained from Google Cloud ConsOAUTH2e for your OIDC application.');
define('_MI_OAUTH2_GOOGLE_OIDC_CLIENT_SECRET', 'Google OIDC Client Secret');
define('_MI_OAUTH2_GOOGLE_OIDC_CLIENT_SECRET_DESC', 'Enter the Client Secret obtained from Google Cloud ConsOAUTH2e.');

// Facebook
define('_MI_OAUTH2_ENABLE_FACEBOOK_AUTH', 'Enable Facebook Login');
define('_MI_OAUTH2_ENABLE_FACEBOOK_AUTH_DESC', 'Allow users to log in using their Facebook accounts.');
define('_MI_OAUTH2_FACEBOOK_APP_ID', 'Facebook App ID');
define('_MI_OAUTH2_FACEBOOK_APP_ID_DESC', 'Enter the App ID obtained from Facebook for Developers.');
define('_MI_OAUTH2_FACEBOOK_APP_SECRET', 'Facebook App Secret');
define('_MI_OAUTH2_FACEBOOK_APP_SECRET_DESC', 'Enter the App Secret obtained from Facebook for Developers.');

// Twitter
define('_MI_OAUTH2_ENABLE_TWITTER_AUTH', 'Enable Twitter Login');
define('_MI_OAUTH2_ENABLE_TWITTER_AUTH_DESC', 'Allow users to log in using their Twitter accounts.');
define('_MI_OAUTH2_TWITTER_API_KEY', 'Twitter API Key (Consumer Key)');
define('_MI_OAUTH2_TWITTER_API_KEY_DESC', 'Enter the API Key from your Twitter Developer App.');
define('_MI_OAUTH2_TWITTER_API_SECRET', 'Twitter API Secret (Consumer Secret)');
define('_MI_OAUTH2_TWITTER_API_SECRET_DESC', 'Enter the API Secret from your Twitter Developer App.');

// Microsoft
define('_MI_OAUTH2_ENABLE_MICROSOFT_AUTH', 'Enable Microsoft Account Login');
define('_MI_OAUTH2_ENABLE_MICROSOFT_AUTH_DESC', 'Allow users to log in using their Microsoft accounts (e.g., Outlook, Hotmail, Live).');
define('_MI_OAUTH2_MICROSOFT_CLIENT_ID', 'Microsoft Application (client) ID');
define('_MI_OAUTH2_MICROSOFT_CLIENT_ID_DESC', 'Enter the Application (client) ID from Azure App Registrations.');
define('_MI_OAUTH2_MICROSOFT_CLIENT_SECRET', 'Microsoft Client Secret');
define('_MI_OAUTH2_MICROSOFT_CLIENT_SECRET_DESC', 'Enter the Client Secret from Azure App Registrations.');

// GitHub
define('_MI_OAUTH2_ENABLE_GITHUB_AUTH', 'Enable GitHub Login');
define('_MI_OAUTH2_ENABLE_GITHUB_AUTH_DESC', 'Allow users to log in using their GitHub accounts. Requires the "simplesamlphp-module-authgithub" module to be installed in SimpleOAUTH2php.');
define('_MI_OAUTH2_GITHUB_CLIENT_ID', 'GitHub Client ID');
define('_MI_OAUTH2_GITHUB_CLIENT_ID_DESC', 'Enter the Client ID from your GitHub OAuth App settings.');
define('_MI_OAUTH2_GITHUB_CLIENT_SECRET', 'GitHub Client Secret');
define('_MI_OAUTH2_GITHUB_CLIENT_SECRET_DESC', 'Enter the Client Secret from your GitHub OAuth App settings.');

// Generic OpenID Connect Provider
define('_MI_OAUTH2_ENABLE_GENERIC_OIDC', 'Enable Generic OpenID Connect Provider');
define('_MI_OAUTH2_ENABLE_GENERIC_OIDC_DESC', 'Allow users to log in using a custom OpenID Connect (OIDC) compliant identity provider.');
define('_MI_OAUTH2_GENERIC_OIDC_DISPLAY_NAME', 'OIDC Provider Display Name');
define('_MI_OAUTH2_GENERIC_OIDC_DISPLAY_NAME_DESC', 'A friendly name for this OIDC provider that will be shown to users (e.g., on a discovery page or login button).');
define('_MI_OAUTH2_GENERIC_OIDC_ISSUER_URL', 'OIDC Issuer URL');
define('_MI_OAUTH2_GENERIC_OIDC_ISSUER_URL_DESC', 'The base URL of the OIDC provider (e.g., https://idp.example.com). This is used for OIDC discovery.');
define('_MI_OAUTH2_GENERIC_OIDC_CLIENT_ID', 'OIDC Client ID');
define('_MI_OAUTH2_GENERIC_OIDC_CLIENT_ID_DESC', 'The Client ID obtained from your OIDC provider.');
define('_MI_OAUTH2_GENERIC_OIDC_CLIENT_SECRET', 'OIDC Client Secret');
define('_MI_OAUTH2_GENERIC_OIDC_CLIENT_SECRET_DESC', 'The Client Secret obtained from your OIDC provider.');
define('_MI_OAUTH2_GENERIC_OIDC_SCOPE', 'OIDC Scopes');
define('_MI_OAUTH2_GENERIC_OIDC_SCOPE_DESC', 'Space-separated list of scopes to request (e.g., openid email profile).');

// Instagram
define('_MI_OAUTH2_ENABLE_INSTAGRAM_AUTH', 'Enable Instagram Login');
define('_MI_OAUTH2_ENABLE_INSTAGRAM_AUTH_DESC', 'Allow users to log in using their Instagram accounts.');
define('_MI_OAUTH2_INSTAGRAM_CLIENT_ID', 'Instagram Client ID');
define('_MI_OAUTH2_INSTAGRAM_CLIENT_ID_DESC', 'Enter the Client ID obtained from Instagram/Facebook for Developers.');
define('_MI_OAUTH2_INSTAGRAM_CLIENT_SECRET', 'Instagram Client Secret');
define('_MI_OAUTH2_INSTAGRAM_CLIENT_SECRET_DESC', 'Enter the Client Secret obtained from Instagram/Facebook for Developers.');
