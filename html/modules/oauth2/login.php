<?php
/**
 * OAuth2 Login Initiator for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../mainfile.php'; // Composer autoloader

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider; // generic OIDC
// Import specific provider classes
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Instagram;
// use League\OAuth2\Client\Provider\LinkedIn;
use Aporat\OAuth2\Client\Provider\XTwitter;
use Trunkstar\OAuth2\Client\Provider\Microsoft;
use PatrickBussmann\OAuth2\Client\Provider\Apple;


global $xoopsModuleConfig, $xoopsConfig;

// provider requested
$providerName = $_GET['provider'] ?? null;

if (empty($providerName)) {
    // TODO redirect to a page that lists available providers
    error_log("OAuth2 Login: No provider specified.");
    // TODO utility function for redirects similar to SamlUtils
    die('OAuth2 Login Error: No provider specified. Please select a login method.');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$provider = null;
$options = []; // provider options like scopes

// Common redirect URI base
// common handler to different providers with different callbacks
$baseRedirectUri = XOOPS_URL . '/modules/oauth2/callback.php'; 

try {
    switch (strtolower($providerName)) {
        case 'google':
            if (empty($xoopsModuleConfig['enable_google_oidc'])) {
                die('Google login is not enabled.');
            }
            $provider = new Google([
                'clientId'     => $xoopsModuleConfig['google_oidc_client_id'],
                'clientSecret' => $xoopsModuleConfig['google_oidc_client_secret'],
                'redirectUri'  => $baseRedirectUri . '?provider=google', // provider for callback
                // 'hostedDomain' => 'example.com', // Optional restrict to users of a G Suite domain
            ]);
            $options['scope'] = ['openid', 'email', 'profile']; // Standard OIDC scopes
            break;

        case 'facebook':
            if (empty($xoopsModuleConfig['enable_facebook_auth'])) {
                die('Facebook login is not enabled.');
            }
            $provider = new Facebook([
                'clientId'          => $xoopsModuleConfig['facebook_app_id'],
                'clientSecret'      => $xoopsModuleConfig['facebook_app_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=facebook',
                'graphApiVersion'   => 'v19.0', // specify version
            ]);
            $options['scope'] = ['email', 'public_profile'];
            break;

        case 'github':
            if (empty($xoopsModuleConfig['enable_github_auth'])) {
                die('GitHub login is not enabled.');
            }
            $provider = new Github([
                'clientId'          => $xoopsModuleConfig['github_client_id'],
                'clientSecret'      => $xoopsModuleConfig['github_client_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=github',
            ]);
            $options['scope'] = ['user:email']; // Request user's primary email
            break;

        case 'instagram':
            if (empty($xoopsModuleConfig['enable_instagram_auth'])) {
                die('Instagram login is not enabled.');
            }
            $provider = new Instagram([
                'clientId'          => $xoopsModuleConfig['instagram_client_id'],
                'clientSecret'      => $xoopsModuleConfig['instagram_client_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=instagram',
                // 'host'              => 'https://api.instagram.com', // Default
            ]);
            // Instagram Basic Display API scopes: user_profile, user_media
            $options['scope'] = ['user_profile'];
            break;

        case 'microsoft':
            if (empty($xoopsModuleConfig['enable_microsoft_auth'])) {
                die('Microsoft login is not enabled.');
            }
            
            $microsoftRedirectUri = $baseRedirectUri . '?provider=microsoft';

            $providerConfig = [
                'clientId'              => $xoopsModuleConfig['microsoft_client_id'],
                'clientSecret'          => $xoopsModuleConfig['microsoft_client_secret'],
                // 'redirectUri'        => $microsoftRedirectUri, // Deprecated in constructor
                // Optional: override default endpoints (e.g., for a specific tenant)
                // add module config options for these if needed.
                // 'urlAuthorize'            => 'https://login.microsoftonline.com/YOUR_TENANT_ID/oauth2/v2.0/authorize',
                // 'urlAccessToken'          => 'https://login.microsoftonline.com/YOUR_TENANT_ID/oauth2/v2.0/token',
                // 'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/me',
                // 'scopes'                  => ['openid', 'profile', 'email', 'User.Read'], // Default scopes
                // 'defaultEndPointVersion'  => Microsoft::ENDPOINT_VERSION_2_0, // Usually default
            ];

            // If module config options for Microsoft endpoints:
            // if (!empty($xoopsModuleConfig['microsoft_authorize_url'])) {
            //     $providerConfig['urlAuthorize'] = $xoopsModuleConfig['microsoft_authorize_url'];
            // }
            // if (!empty($xoopsModuleConfig['microsoft_token_url'])) {
            //     $providerConfig['urlAccessToken'] = $xoopsModuleConfig['microsoft_token_url'];
            // }
            // if (!empty($xoopsModuleConfig['microsoft_resource_owner_url'])) {
            //     $providerConfig['urlResourceOwnerDetails'] = $xoopsModuleConfig['microsoft_resource_owner_url'];
            // }

            $provider = new Microsoft($providerConfig);
            
            // Common scopes for Microsoft Graph API
            // Pass redirect_uri here as per Trunkstar provider's recommendation
            $options = [
                'scope' => ['openid', 'profile', 'email', 'User.Read'],
                'redirect_uri' => $microsoftRedirectUri 
            ];
            break;

        
        case 'apple':
            if (empty($xoopsModuleConfig['enable_apple_auth'])) {
                die('Sign in with Apple is not enabled.');
            }
            if (empty($xoopsModuleConfig['apple_key_file_path']) || !file_exists($xoopsModuleConfig['apple_key_file_path'])) {
                error_log("Apple Sign-In: Private key file not found or path not configured: " . ($xoopsModuleConfig['apple_key_file_path'] ?? 'Not Set'));
                die('Apple Sign-In configuration error (key file). Please contact an administrator.');
            }
            $provider = new Apple([
                'clientId'                 => $xoopsModuleConfig['apple_client_id'],
                'teamId'                   => $xoopsModuleConfig['apple_team_id'],
                'keyFileId'                => $xoopsModuleConfig['apple_key_file_id'],
                'keyFilePath'              => $xoopsModuleConfig['apple_key_file_path'], // Absolute path to .p8 key file
                'redirectUri'              => $xoopsModuleConfig['apple_redirect_uri'],
            ]);
            $options['scope'] = ['name', 'email'];
            break;

        case 'twitter':
            if (empty($xoopsModuleConfig['enable_twitter_auth'])) {
                die('Twitter login is not enabled.');
            }
            $provider = new XTwitter([
                'clientId'          => $xoopsModuleConfig['twitter_api_key'],
                'clientSecret'      => $xoopsModuleConfig['twitter_api_secret'],
                'redirectUri'       => $baseRedirectUri . '?provider=twitter',
            ]);
            // Scopes for Twitter OAuth 2.0 (PKCE flow)
            // Check aporat/oauth2-xtwitter documentation for exact scope names
            $options['scope'] = ['users.read', 'tweet.read', 'offline.access']; 
            break;


        // cases for Generic OIDC etc.
        default:
            error_log("OAuth2 Login: Unsupported provider requested: " . htmlspecialchars($providerName));
            die('OAuth2 Login Error: Unsupported provider.');
    }

    if (!$provider) {
        // if any enable checks above
        die('OAuth2 Login Error: Provider could not be initialized.');
    }

    // Fetch the authorization URL from the provider
    // returns urlAuthorize option, generates and stores state parameter
    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Store the state generated by the provider in the session.
    // Store provider for callback verification
    $_SESSION['oauth2state'] = $provider->getState();
    $_SESSION['oauth2provider'] = strtolower($providerName); 

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

} catch (IdentityProviderException $e) {
    // Failed to get the authorization URL or other provider-specific error.
    error_log("OAuth2 Login Provider Exception for " . htmlspecialchars($providerName) . ": " . $e->getMessage());
    // Consider a more user-friendly error page
    die('An error occurred while trying to connect to ' . htmlspecialchars(ucfirst($providerName)) . '. Please try again later. Details: ' . $e->getMessage());
} catch (\Exception $e) {
    // Catch any other general exceptions
    error_log("OAuth2 Login General Exception for " . htmlspecialchars($providerName) . ": " . $e->getMessage());
    die('An unexpected error occurred during login initiation. Please contact an administrator.');
}
