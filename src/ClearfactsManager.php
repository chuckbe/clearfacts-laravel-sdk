<?php

namespace Clearfacts;

use Clearfacts\Api\ClearfactsApiClient;

class ClearfactsManager
{
    protected ?ClearfactsApiClient $client = null;

    protected ?string $accessToken = null;

    public function __construct()
    {
        //
    }

    /**
     * Set the access token for the current tenant/user.
     * In a multi-tenant SaaS, call this with the token
     * retrieved from your OIDC flow for the current tenant.
     */
    public function setAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;
        $this->client = null; // Reset client so it picks up the new token

        return $this;
    }

    /**
     * Get the Clearfacts API client (Saloon connector).
     * Provides fluent access to all API resources:
     *
     * Clearfacts::api()->document->upload($data);
     * Clearfacts::api()->administration->list();
     */
    public function api(): ClearfactsApiClient
    {
        if ($this->client === null) {
            if ($this->accessToken === null) {
                throw new \RuntimeException(
                    'No access token set. Call Clearfacts::setAccessToken($token) before making API requests. '
                    .'Retrieve the token from your OIDC/Socialite flow for the current tenant.'
                );
            }

            $this->client = new ClearfactsApiClient(
                accessToken: $this->accessToken,
                baseUrl: config('clearfacts.base_url', 'https://api.clearfacts.be'),
            );
        }

        return $this->client;
    }

    /**
     * Get the currently set access token.
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
