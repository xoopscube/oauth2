# X (formerly Twitter) Provider for OAuth 2.0 Client

[![Latest Stable Version](https://img.shields.io/packagist/v/aporat/oauth2-xtwitter.svg?logo=composer)](https://packagist.org/packages/aporat/oauth2-xtwitter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![codecov](https://codecov.io/github/aporat/oauth2-xtwitter/graph/badge.svg?token=BHD3JZS4LQ)](https://codecov.io/github/aporat/oauth2-xtwitter)
![GitHub Actions Workflow Status](https://github.com/aporat/oauth2-xtwitter/actions/workflows/ci.yml/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/aporat/oauth2-xtwitter.svg)](https://packagist.org/packages/aporat/oauth2-xtwitter)

This package provides **X (formerly Twitter)** OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

Install via Composer:

```bash
composer require aporat/oauth2-xtwitter
```

## Usage

Usage follows The League's OAuth 2.0 client style, using `\Aporat\OAuth2\Client\Provider\XTwitter` as the provider.

### Authorization Code Flow

```php
$provider = new Aporat\OAuth2\Client\Provider\XTwitter([
    'clientId'     => '{x-client-id}',
    'clientSecret' => '{x-client-secret}',
    'redirectUri'  => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    $_SESSION['oauth2pkceCode'] = $provider->getPkceCode();
 
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    // Check given state against previously stored one to mitigate CSRF attack
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    $provider->setPkceCode($_SESSION['oauth2pkceCode']);
    
    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);

    // Optional: Now you have a token you can look up a user's profile data
    try {
        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());
    } catch (Exception $e) {
        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the user's behalf
    echo $token->getToken();
}
```

### Managing Scopes

When creating your X (formerly Twitter) authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['tweet.read', 'users.read'] // Adjust scopes as needed
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/aporat/oauth2-xtwitter/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see the [License File](https://github.com/aporat/oauth2-xtwitter/blob/master/LICENSE) for more information.
