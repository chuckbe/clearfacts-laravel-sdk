<?php

namespace Clearfacts\Api\Requests\Customer;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetCustomersRequest extends GraphQLRequest
{
    public function __construct(
        protected string $companyNumber,
        protected ?int $offset = null,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query customers ($companyNumber: String!, $offset: Int) {
            customers (companyNumber: $companyNumber, offset: $offset) {
                id,
                companyNumber,
                name,
                address {
                    streetAddress,
                    extendedAddress,
                    postalCode,
                    locality,
                    country {
                        iso2,
                        name
                    }
                },
                email,
                phone,
                fax
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return array_filter([
            'companyNumber' => $this->companyNumber,
            'offset' => $this->offset,
        ], fn ($value) => $value !== null);
    }
}
