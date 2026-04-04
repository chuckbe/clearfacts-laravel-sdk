<?php

namespace Clearfacts\Api\Requests\Accountant;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetAccountantRequest extends GraphQLRequest
{
    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query accountant {
            accountant {
                name,
                companyNumber,
                email,
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
}
