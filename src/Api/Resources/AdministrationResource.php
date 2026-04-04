<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Administration\GetAdministrationRequest;
use Clearfacts\Api\Requests\Administration\GetAdministrationsRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\Administration;
use Saloon\Http\Connector;

class AdministrationResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get all administrations the authenticated user has access to.
     *
     * @return Administration[]
     */
    public function list(): array
    {
        $response = $this->connector->send(new GetAdministrationsRequest());

        return array_map(
            fn (array $item) => Administration::fromArray($item),
            $this->extractData($response, 'administrations'),
        );
    }

    /**
     * Get a single administration by company number.
     */
    public function get(string $companyNumber): Administration
    {
        $response = $this->connector->send(new GetAdministrationRequest($companyNumber));

        return Administration::fromArray(
            $this->extractData($response, 'administration'),
        );
    }
}
