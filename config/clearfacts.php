<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clearfacts API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Clearfacts GraphQL API.
    |
    */

    'base_url' => env('CLEARFACTS_API_URL', 'https://api.clearfacts.be'),

    /*
    |--------------------------------------------------------------------------
    | Clearfacts GraphQL Endpoint
    |--------------------------------------------------------------------------
    |
    | The GraphQL endpoint path appended to the base URL.
    |
    */

    'graphql_endpoint' => env('CLEARFACTS_GRAPHQL_ENDPOINT', '/graphql'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Clearfacts uses OpenID Connect (Authorization Code Flow) for
    | authentication. Configure your OIDC client credentials here.
    |
    | The SDK expects you to handle OIDC authentication via Laravel Socialite
    | or a similar package and provide the access token per-tenant.
    |
    */

    'auth' => [
        'client_id' => env('CLEARFACTS_CLIENT_ID'),
        'client_secret' => env('CLEARFACTS_CLIENT_SECRET'),
        'redirect_uri' => env('CLEARFACTS_REDIRECT_URI'),

        // OpenID Connect discovery endpoint
        // Default: https://login.clearfacts.be
        // For a specific accountant: https://acme.clearfacts.be
        'issuer' => env('CLEARFACTS_ISSUER', 'https://login.clearfacts.be'),

        // Well-known OIDC discovery URL (auto-derived from issuer)
        // https://login.clearfacts.be/.well-known/openid-configuration

        // Scopes to request during OIDC authorization
        // OpenID scopes: openid, email, profile
        // API scopes: accountant, statistics, read_administrations,
        //   associate_read, associate_actions, journal_read, contact_read,
        //   upload_document, archive_read, archive_actions, archive
        'scopes' => [
            'openid',
            'email',
            'profile',
            'accountant',
            'statistics',
            'read_administrations',
            'associate_read',
            'associate_actions',
            'journal_read',
            'contact_read',
            'upload_document',
            'archive_read',
            'archive_actions',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests.
    |
    */

    'timeout' => env('CLEARFACTS_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic retry behaviour for failed requests.
    |
    */

    'retry' => [
        'times' => env('CLEARFACTS_RETRY_TIMES', 3),
        'interval' => env('CLEARFACTS_RETRY_INTERVAL', 500),
    ],

];
