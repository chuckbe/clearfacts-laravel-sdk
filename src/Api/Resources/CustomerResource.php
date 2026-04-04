<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Customer\GetCustomersRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\BusinessPartner;
use Saloon\Http\Connector;

class CustomerResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get all customers (business partners) for a specific administration.
     * Results are paginated (max 100 per request). Use offset for paging.
     *
     * @return BusinessPartner[]
     */
    public function list(string $companyNumber, ?int $offset = null): array
    {
        $response = $this->connector->send(new GetCustomersRequest($companyNumber, $offset));

        return array_map(
            fn (array $item) => BusinessPartner::fromArray($item),
            $this->extractData($response, 'customers'),
        );
    }
}
