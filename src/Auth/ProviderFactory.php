<?php

namespace App\Auth;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use League\OAuth2\Client\Provider\Github;

class ProviderFactory
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    public function __construct(UrlGeneratorInterface $generator, string $clientId, string $clientSecret)
    {
        $this->generator = $generator;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function githubProvider(): Github
    {
        return new Github([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret
        ]);
    }
}
