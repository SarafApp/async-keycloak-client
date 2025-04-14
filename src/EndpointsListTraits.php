<?php

namespace Saraf\AsyncKeycloakClient;

trait EndpointsListTraits
{
    private string $realm;

    public function getTokenEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/token', $this->realm);
    }

    public function getIntrospectEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/token/introspect', $this->realm);
    }

    public function getLogoutEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/logout', $this->realm);
    }

    public function getAuthorizationEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/auth', $this->realm);
    }

    public function getUserInfoEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/userinfo', $this->realm);
    }

    public function getJwksUri(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/certs', $this->realm);
    }

    public function getEndSessionEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/logout', $this->realm);
    }

    public function getRegistrationEndpoint(): string
    {
        return sprintf('/realms/%s/clients-registrations/openid-connect', $this->realm);
    }
}