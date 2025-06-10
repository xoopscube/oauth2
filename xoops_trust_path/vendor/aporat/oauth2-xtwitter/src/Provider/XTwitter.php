<?php

namespace Aporat\OAuth2\Client\Provider;

use Aporat\OAuth2\Client\Provider\Exception\XTwitterIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

final class XTwitter extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected function getPkceMethod(): ?string
    {
        return AbstractProvider::PKCE_METHOD_S256;
    }

    /**
     * Domain
     *
     * @var string
     */
    public string $domain = 'https://x.com';

    /**
     * Api domain
     *
     * @var string
     */
    public string $apiDomain = 'https://api.x.com';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/i/oauth2/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->apiDomain . '/2/oauth2/token';
    }

    /**
     * Get provider url to fetch user details
     *
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->apiDomain . '/2/users/me?user.fields=id,name,username,profile_image_url,confirmed_email';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return [
            'tweet.read',
            'users.email',
            'users.read',
            'offline.access'
        ];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Contains one space (` `)
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array $data Parsed response data
     * @return void
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new XTwitterIdentityProviderException(
                $data['message'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return XTwitterResourceOwner
     */
    protected function createResourceOwner(
        array $response,
        AccessToken $token
    ): XTwitterResourceOwner {
        return new XTwitterResourceOwner($response);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ];
    }
}
