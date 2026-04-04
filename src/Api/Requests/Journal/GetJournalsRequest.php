<?php

namespace Clearfacts\Api\Requests\Journal;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetJournalsRequest extends GraphQLRequest
{
    public function __construct(
        protected string $companyNumber,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query journals ($companyNumber: String!) {
            journals (companyNumber: $companyNumber) {
                id,
                name,
                creditNote,
                type,
                default,
                lastDocumentNumber
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
