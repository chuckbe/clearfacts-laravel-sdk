<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Accountant\GetAccountantRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\Accountant;
use Saloon\Http\Connector;

class AccountantResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get the accountant linked to the authenticated user.
     */
    public function get(): Accountant
    {
        $response = $this->connector->send(new GetAccountantRequest());

        return Accountant::fromArray(
            $this->extractData($response, 'accountant'),
        );
    }
}
