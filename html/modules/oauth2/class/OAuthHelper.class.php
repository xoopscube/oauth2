<?php
/**
 * OAuth2 User Handler for XOOPSCube
 * 
 * @package    oauth2
 * @version    XCL 2.5.0
 * @author     Nuno Luciano aka gigamaster
 * @copyright  (c) 2005-2024 The XOOPSCube Project
 * @license    GPL 2.0
 */

declare(strict_types=1);

class OAuthHelper
{
    /**
     * Gets a list of available and enabled OAuth2 providers.
     *
     * @param array $moduleConfig The module's configuration array.
     * @param string $moduleDirname The directory name of the module.
     * @return array
     */
    public static function getAvailableProviders(array $moduleConfig, string $moduleDirname): array
    {
        $available_providers = [];
        $baseUrl = XOOPS_URL . '/modules/' . $moduleDirname;

        // Google (league/oauth2-google)
        if (!empty($moduleConfig['enable_google_oidc'])) {
            $available_providers[] = [
                'name' => 'Google',
                'key'  => 'google',
                'url'  => $baseUrl . '/login.php?provider=google',
                'icon' => $baseUrl . '/images/google_icon.svg'
            ];
        }
        // Facebook (league/oauth2-facebook)
        if (!empty($moduleConfig['enable_facebook_auth'])) {
            $available_providers[] = [
                'name' => 'Facebook',
                'key'  => 'facebook',
                'url'  => $baseUrl . '/login.php?provider=facebook',
                'icon' => $baseUrl . '/images/facebook_icon.svg'
            ];
        }
        // GitHub (league/oauth2-github)
        if (!empty($moduleConfig['enable_github_auth'])) {
            $available_providers[] = [
                'name' => 'GitHub',
                'key'  => 'github',
                'url'  => $baseUrl . '/login.php?provider=github',
                'icon' => $baseUrl . '/images/github_icon.svg'
            ];
        }
        // Instagram (league/oauth2-instagram)
        if (!empty($moduleConfig['enable_instagram_auth'])) {
            $available_providers[] = [
                'name' => 'Instagram',
                'key'  => 'instagram',
                'url'  => $baseUrl . '/login.php?provider=instagram',
                'icon' => $baseUrl . '/images/instagram_icon.svg'
            ];
        }
        // Microsoft (trunkstar/oauth2-microsoft)
        if (!empty($moduleConfig['enable_microsoft_auth'])) {
            $available_providers[] = [
                'name' => 'Microsoft',
                'key'  => 'microsoft',
                'url'  => $baseUrl . '/login.php?provider=microsoft',
                'icon' => $baseUrl . '/images/microsoft_icon.svg'
            ];
        }
        // Apple (patrickbussmann/oauth2-apple)
        if (!empty($moduleConfig['enable_apple_auth'])) {
            $available_providers[] = [
                'name' => 'Apple',
                'key'  => 'apple',
                'url'  => $baseUrl . '/login.php?provider=apple',
                'icon' => $baseUrl . '/images/apple_icon.svg'
            ];
        }
        // Twitter (aporat/oauth2-xtwitter)
        if (!empty($moduleConfig['enable_twitter_auth'])) {
            $available_providers[] = [
                'name' => 'X (Twitter)',
                'key'  => 'twitter',
                'url'  => $baseUrl . '/login.php?provider=twitter',
                'icon' => $baseUrl . '/images/x-twitter_icon.svg'
            ];
        }
        // Generic OIDC (league/oauth2-client GenericProvider)
        if (!empty($moduleConfig['enable_generic_oidc'])) {
            $displayName = !empty($moduleConfig['generic_oidc_display_name']) ? $moduleConfig['generic_oidc_display_name'] : 'OpenID Connect';
            $available_providers[] = [
                'name' => htmlspecialchars($displayName, ENT_QUOTES),
                'key'  => 'generic-oidc',
                'url'  => $baseUrl . '/login.php?provider=generic-oidc',
                'icon' => $baseUrl . '/images/openid_icon.svg'
            ];
        }

        return $available_providers;
    }
}
