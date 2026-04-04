<?php

namespace Clearfacts\Api\Requests\Associate;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetAssociatesRequest extends GraphQLRequest
{
    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query associates {
            associates {
                id,
                firstName,
                lastName,
                email,
                language,
                type,
                active,
                associateGroups {
                    id,
                    name
                }
            }
        }
        GRAPHQL;
    }
}
