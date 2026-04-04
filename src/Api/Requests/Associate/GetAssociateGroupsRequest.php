<?php

namespace Clearfacts\Api\Requests\Associate;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetAssociateGroupsRequest extends GraphQLRequest
{
    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query associateGroups {
            associateGroups {
                id
                name
            }
        }
        GRAPHQL;
    }
}
