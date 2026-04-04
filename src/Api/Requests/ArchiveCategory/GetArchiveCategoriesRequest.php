<?php

namespace Clearfacts\Api\Requests\ArchiveCategory;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetArchiveCategoriesRequest extends GraphQLRequest
{
    public function __construct(
        protected string $vatNumber,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query categories ($vatNumber: String!) {
            archiveCategories (vatnumber: $vatNumber) {
                various {
                    id,
                    name
                },
                permanent {
                    id,
                    name
                }
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return [
            'vatNumber' => $this->vatNumber,
        ];
    }
}
