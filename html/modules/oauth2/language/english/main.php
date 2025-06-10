<?php

define('_MD_OAUTH2_TITLE','OAUTH2 Login');
define('_MD_OAUTH2_LOGIN','Login with an external account');
define('_MD_OAUTH2_SELECT_PROVIDER','Select a provider to log-in or register');
define('_MD_OAUTH2_NO_PROVIDERS','No providers enabled.');

// OAuth2 Callback Errors
define('_MD_OAUTH2_CALLBACK_ERROR_NO_PROVIDER', 'OAuth2 Callback Error: Could not determine the authentication provider. Please try logging in again.');
define('_MD_OAUTH2_CALLBACK_ERROR_INVALID_STATE', 'Invalid state. Please try logging in again.');
define('_MD_OAUTH2_CALLBACK_ERROR_NO_CODE', 'OAuth2 Error: Authorization failed or was denied by the provider. (%s)');
define('_MD_OAUTH2_CALLBACK_ERROR_UNSUPPORTED_PROVIDER', 'OAuth2 Callback Error: Unsupported provider.');
define('_MD_OAUTH2_CALLBACK_ERROR_PROVIDER_INIT', 'OAuth2 Callback Error: Provider could not be initialized.');
define('_MD_OAUTH2_CALLBACK_ERROR_REGISTRATION', 'Error during user registration. Please contact an administrator.');
define('_MD_OAUTH2_CALLBACK_ERROR_LOGIN_FAILED', 'Login failed after authentication. Please contact an administrator.');
define('_MD_OAUTH2_CALLBACK_ERROR_NO_USER_ACCOUNT', 'Unable to log you in. Your account may not exist or new registrations are not allowed. Please contact an administrator.');
define('_MD_OAUTH2_CALLBACK_ERROR_AUTHENTICATING_WITH', 'Error authenticating with %s. Details: %s');
define('_MD_OAUTH2_CALLBACK_ERROR_UNEXPECTED', 'An unexpected error occurred during callback processing. Please contact an administrator.');
