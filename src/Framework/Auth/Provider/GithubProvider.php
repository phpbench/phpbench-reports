<?php

namespace App\Framework\Auth\Provider;

use League\OAuth2\Client\Provider\Github;
use App\Framework\Auth\Provider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

class GithubProvider implements Provider
{
    /**
     * @var Github
     */
    private $innerProvider;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->innerProvider = new Github([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret
        ]);
    }

    public function resourceOwner(AccessToken $token): ResourceOwnerInterface
    {
        return $this->innerProvider->getResourceOwner($token);
    }

    public function accessToken(string $code): AccessToken
    {
        return $this->innerProvider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
    }

    public function authorizationUrl(): string
    {
        return $this->innerProvider->getAuthorizationUrl();
    }
}
