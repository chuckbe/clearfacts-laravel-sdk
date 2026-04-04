<?php

namespace Clearfacts\Api\Requests\Administration;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetAdministrationsRequest extends GraphQLRequest
{
    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query admins {
            administrations {
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
}
