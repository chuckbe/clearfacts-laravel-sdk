<?php

namespace Clearfacts\Api\Requests\Administration;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetAdministrationRequest extends GraphQLRequest
{
    public function __construct(
        protected string $companyNumber,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query admin ($companyNumber: String!) {
            administration (companyNumber: $companyNumber) {
                name,
                companyNumber,
                accountManager,
                emails {
                    type,
                    emailAddress
                },
                address {
                    streetAddress,
                    country {
                        iso2,
                        name
                    }
                }
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return [
            'companyNumber' => $this->companyNumber,
        ];
    }
}
