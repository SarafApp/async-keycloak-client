<?php

namespace Saraf\AsyncKeycloakClient;

use React\Promise\PromiseInterface;
use Saraf\AsyncRequest;

trait UserInfoTrait
{
    protected AsyncRequest $http;

    public function userInfoEndpoint(): string
    {
        return sprintf('/realms/%s/protocol/openid-connect/userinfo', $this->configurations->getRealm());
    }

    public function getUserInfo(string $token): PromiseInterface
    {
        return $this->http->get($this->userInfoEndpoint(), [], [
            'Authorization' => 'Bearer ' . $token
        ])->then(function ($result) {
            if (!$result['result']) {
                return [
                    'result' => false,
                    'error' => $result['error']
                ];
            }

            $body = json_decode($result['body'], true);
            if ($result['code'] != 200) {
                return [
                    'result' => false,
                    'error' => $body['error']
                ];
            }

            return [
                'result' => true,

                'username' => $body['username'],
                'email' => $body['email'],

                'firstName' => $body['firstName'],
                'lastName' => $body['lastName'],
                'fullName' => $body['name'],

                'roles' => $body['realm_access']['roles'],
            ];
        });
    }
}