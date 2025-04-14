<?php

namespace Saraf\AsyncKeycloakClient;

class KeycloakConfigurations
{
    private string $baseUrl;

    private string $realm;
    private ?string $scope;

    private string $clientId;
    private string $clientSecret;

    private bool $verifySSL;
    private int $timeout;
    private string $redirectURL;

    use EndpointsListTraits;

    public function __construct(
        string  $baseUrl,
        string  $realm,
        string  $clientId,
        string  $clientSecret,
        string  $redirectURL,
        ?string $scope = null,
        bool    $verifySSL = true,
        int     $timeout = 30
    )
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->realm = $realm;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scope = $scope ?? 'openid';
        $this->verifySSL = $verifySSL;
        $this->timeout = $timeout;
        $this->redirectURL = $redirectURL;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getRealm(): string
    {
        return $this->realm;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function isVerifySSL(): bool
    {
        return $this->verifySSL;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getRedirectURL(): string
    {
        return $this->redirectURL;
    }
}