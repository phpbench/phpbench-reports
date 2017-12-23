<?php

namespace App\Framework\Auth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

interface Provider
{
    public function resourceOwner(AccessToken $token): ResourceOwnerInterface;

    public function accessToken(string $code): AccessToken;

    public function authorizationUrl(): string;
}
