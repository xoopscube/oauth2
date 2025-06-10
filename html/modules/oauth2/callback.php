<?php
/**
 * OAuth2 Callback Handler for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../mainfile.php'; 

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Instagram;
use Aporat\OAuth2\Client\Provider\XTwitter;
use Trunkstar\OAuth2\Client\Provider\Microsoft;
use PatrickBussmann\OAuth2\Client\Provider\Apple;

global $xoopsModuleConfig, $xoopsConfig, $xoopsUser;

// Start session for OAuth2 state and provider
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$providerNameFromSession = $_SESSION['oauth2provider'] ?? null;
$providerNameFromRequest = $_GET['provider'] ?? null; // Provider name might also be in GET

$providerName = $providerNameFromSession ?: $providerNameFromRequest;

if (empty($providerName)) {
    error_log("OAuth2 Callback: Provider name not found in session or request.");
    // TODO redirect error page or the login page
    die(_MD_OAUTH2_CALLBACK_ERROR_NO_PROVIDER);
}

// Security check: Verify state parameter
if (empty($_GET['state']) || !isset($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    // clear provider from session if fails
    if (isset($_SESSION['oauth2provider'])) {
        unset($_SESSION['oauth2provider']);
    }
    error_log("OAuth2 Callback: Invalid state parameter for provider: " . htmlspecialchars($providerName));
    die(_MD_OAUTH2_CALLBACK_ERROR_INVALID_STATE);
}

// Clear the state from session
unset($_SESSION['oauth2state']);

// Check authorization code
if (!isset($_GET['code'])) {
    // user denied access or an error occurred at the provider
    $error = $_GET['error'] ?? 'Unknown error';
    $errorDescription = $_GET['error_description'] ?? 'The provider did not return an authorization code.';
    error_log("OAuth2 Callback: No authorization code. Provider: " . htmlspecialchars($providerName) . ". Error: " . htmlspecialchars($error) . ". Description: " . htmlspecialchars($errorDescription));
    if (isset($_SESSION['oauth2provider'])) { // Clean up provider if failed
        unset($_SESSION['oauth2provider']);
    }
    die(sprintf(_MD_OAUTH2_CALLBACK_ERROR_NO_CODE, htmlspecialchars($error)));
}

// Initialize OAuth2 provider
$provider = null;
// Must match what was used in login.php
$baseRedirectUri = XOOPS_URL . '/modules/oauth2/callback.php'; 

try {
    switch (strtolower($providerName)) {
        case 'google':
            $provider = new Google([
                'clientId'     => $xoopsModuleConfig['google_oidc_client_id'],
                'clientSecret' => $xoopsModuleConfig['google_oidc_client_secret'],
                'redirectUri'  => $baseRedirectUri . '?provider=google',
            ]);
            break;
        case 'facebook':
            $provider = new Facebook([
                'clientId'          => $xoopsModuleConfig['facebook_app_id'],
                'clientSecret'      => $xoopsModuleConfig['facebook_app_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=facebook',
                'graphApiVersion'   => 'v19.0',
            ]);
            break;
        case 'github':
            $provider = new Github([
                'clientId'          => $xoopsModuleConfig['github_client_id'],
                'clientSecret'      => $xoopsModuleConfig['github_client_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=github',
            ]);
            break;
        case 'instagram':
            $provider = new Instagram([
                'clientId'          => $xoopsModuleConfig['instagram_client_id'],
                'clientSecret'      => $xoopsModuleConfig['instagram_client_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=instagram',
            ]);
            break;
        case 'twitter':
            $provider = new XTwitter([
                'clientId'          => $xoopsModuleConfig['twitter_api_key'],
                'clientSecret'      => $xoopsModuleConfig['twitter_api_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=twitter',
            ]);
            break;
        case 'microsoft':
             $providerConfig = [
                'clientId'              => $xoopsModuleConfig['microsoft_client_id'],
                'clientSecret'          => $xoopsModuleConfig['microsoft_client_secret'],
            ];
            $provider = new Microsoft($providerConfig);
            break;
        case 'apple':
            $appleRedirectUri = $xoopsModuleConfig['apple_redirect_uri'] ?? ($baseRedirectUri . '?provider=apple');
            $provider = new Apple([
                'clientId'                 => $xoopsModuleConfig['apple_client_id'],
                'teamId'                   => $xoopsModuleConfig['apple_team_id'],
                'keyFileId'                => $xoopsModuleConfig['apple_key_file_id'],
                'keyFilePath'              => $xoopsModuleConfig['apple_key_file_path'],
                'redirectUri'              => $appleRedirectUri,
            ]);
            break;
        default:
            error_log("OAuth2 Callback: Unsupported provider in session during provider initialization: " . htmlspecialchars($providerName));
            if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
            die(_MD_OAUTH2_CALLBACK_ERROR_UNSUPPORTED_PROVIDER);
    }

    if (!$provider) {
        if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
        die(_MD_OAUTH2_CALLBACK_ERROR_PROVIDER_INIT);
    }

    // authorization code for an access token
    $tokenOptions = ['code' => $_GET['code']];
    if (strtolower($providerName) === 'microsoft') {
        // Microsoft provider expects redirect_uri
        $tokenOptions['redirect_uri'] = $baseRedirectUri . '?provider=microsoft';
    }
    $accessToken = $provider->getAccessToken('authorization_code', $tokenOptions);

    // Fetch resource owner (user) details
    $resourceOwner = $provider->getResourceOwner($accessToken);

    // User Login/Registration Logic
    require_once XOOPS_ROOT_PATH . '/modules/oauth2/class/OAuthUserHandler.class.php';

    $oauthUserHandler = new OAuthUserHandler();

    $externalId = $resourceOwner->getId(); 
    $providerNameLower = strtolower($providerName); 

    $xoopsUid = $oauthUserHandler->findXoopsUidByExternalId($externalId, $providerNameLower);
    $xoopsUserToLogin = null;

    if ($xoopsUid) {
        $xoopsUserToLogin = $oauthUserHandler->xoopsUserHandler->get($xoopsUid);
        if (!is_object($xoopsUserToLogin) || $xoopsUserToLogin->getVar('level') == 0) {
            error_log("OAuth2 Callback: Mapped XOOPS user UID {$xoopsUid} not found or inactive for external ID {$externalId} ({$providerNameLower}).");
            $xoopsUserToLogin = null; 
        }
    } else {
        $email = method_exists($resourceOwner, 'getEmail') ? $resourceOwner->getEmail() : null;
        if ($email && ($xoopsModuleConfig['oauth2_email_fallback_lookup'] ?? 0)) { 
            $existingUserByEmail = $oauthUserHandler->findUserByEmail($email);
            if ($existingUserByEmail) {
                $xoopsUserToLogin = $existingUserByEmail;
                if (!$oauthUserHandler->createMapping($xoopsUserToLogin->getVar('uid'), $externalId, $providerNameLower)) {
                    error_log("OAuth2 Callback: Failed to create mapping for existing user UID " . $xoopsUserToLogin->getVar('uid') . " by email {$email} to external ID {$externalId} ({$providerNameLower}).");
                    // fatal error or login can proceed
                } else {
                    error_log("OAuth2 Callback: Linked existing XOOPS user UID " . $xoopsUserToLogin->getVar('uid') . " by email {$email} to external ID {$externalId} ({$providerNameLower}).");
                }
            }
        }

        // Check if $this->moduleConfig is accessible
        if (!$xoopsUserToLogin && ($xoopsModuleConfig['allow_register'] ?? 0)) { 
            $attributes = $resourceOwner->toArray(); 
            $xoopsUserToLogin = $oauthUserHandler->registerNewUser($attributes, $externalId, $providerNameLower);
            if ($xoopsUserToLogin) {
                 error_log("OAuth2 Callback: Registered new XOOPS user UID " . $xoopsUserToLogin->getVar('uid') . " for external ID {$externalId} ({$providerNameLower}).");
            } else {
                error_log("OAuth2 Callback: Failed to register new user for external ID {$externalId} ({$providerNameLower}).");
                if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
                die(_MD_OAUTH2_CALLBACK_ERROR_REGISTRATION); 
            }
        }
    }

    if ($xoopsUserToLogin && is_object($xoopsUserToLogin) && $xoopsUserToLogin->getVar('level') > 0) {
        if ($oauthUserHandler->loginXoopsUser($xoopsUserToLogin)) {
            // Successful login
            unset($_SESSION['oauth2provider']); // Clean up provider from session
            $redirectUrl = $xoopsModuleConfig['oauth2_login_redirect_url'] ?? XOOPS_URL . '/'; 
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            error_log("OAuth2 Callback: Failed to log in XOOPS user UID " . $xoopsUserToLogin->getVar('uid'));
            if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
            die(_MD_OAUTH2_CALLBACK_ERROR_LOGIN_FAILED);
        }
    } else {
        error_log("OAuth2 Callback: No XOOPS user found or could be registered for external ID {$externalId} ({$providerNameLower}). Registration allowed: " . ($xoopsModuleConfig['allow_register'] ?? 0));
        if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
        die(_MD_OAUTH2_CALLBACK_ERROR_NO_USER_ACCOUNT);
    }

} catch (IdentityProviderException $e) {
    error_log("OAuth2 Callback IdentityProviderException for " . htmlspecialchars($providerName) . ": " . $e->getMessage() . "\nResponse body: " . $e->getResponseBody());
    if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
    die(_MD_OAUTH2_CALLBACK_ERROR_AUTHENTICATING_WITH);
} catch (\Exception $e) {
    error_log("OAuth2 Callback General Exception for " . htmlspecialchars($providerName) . ": " . $e->getMessage());
    if (isset($_SESSION['oauth2provider'])) { unset($_SESSION['oauth2provider']); }
    die(_MD_OAUTH2_CALLBACK_ERROR_UNEXPECTED);
}
