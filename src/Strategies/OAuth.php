<?php

namespace Saraf\AsyncKeycloakClient\Strategies;

use React\Promise\PromiseInterface;
use Saraf\AsyncKeycloakClient\KeycloakConfigurations;
use Saraf\AsyncKeycloakClient\UserInfoTrait;
use Saraf\AsyncRequest;
use Saraf\RequestBody\UrlEncodedFormData;

class OAuth
{
    protected AsyncRequest $http;
    protected KeycloakConfigurations $configurations;

    use UserInfoTrait;

    /**
     * @throws \Exception
     */
    public function __construct(
        KeycloakConfigurations $configurations
    )
    {
        $this->configurations = $configurations;
        $this->http = new AsyncRequest();
        $this->http->setConfig([
            'baseURL' => $this->configurations->getBaseURL(),
        ]);
    }

    public function getOAuthRedirectUrl(?string $scope = null): string
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->configurations->getClientId(),
            'redirect_uri' => $this->configurations->getRedirectURL(),
            'scope' => $scope ?? $this->configurations->getScope(),
        ];

        return $this->configurations->getBaseUrl() . $this->configurations->getAuthorizationEndpoint() . '?' . http_build_query($params, "", '&');
    }

    public function convertCodeToToken(string $code, ?string $state = null): PromiseInterface
    {
        $body = UrlEncodedFormData::create([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->configurations->getClientId(),
            'client_secret' => $this->configurations->getClientSecret(),
            'redirect_uri' => $this->configurations->getRedirectURL(),
        ]);
        return $this->http->post($this->configurations->getTokenEndpoint(), $body, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->then(function ($result) {
            if (!$result['result']) {
                return [
                    'result' => false,
                    'error' => $result['error']
                ];
            }

            $body = json_decode($result['body'], true);

            if ($result['code'] !== 200) {
                return [
                    'result' => false,
                    'error' => $body['error'],
                    'error_description' => $body['error_description']
                ];
            }

            return [
                'result' => true,
                'access_token' => $body['access_token'],
                'refresh_token' => $body['refresh_token'],
                'expires_in' => $body['expires_in'],
                'refresh_expires_in' => $body['refresh_expires_in'],
                'token_type' => $body['token_type'],
                'session_state' => $body['session_state'],
                'scope' => $body['scope'],
                'id_token' => $body['id_token'],
            ];
        });
    }
}