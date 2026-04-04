<?php

namespace Clearfacts\Api\Requests\Document;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetDocumentRequest extends GraphQLRequest
{
    public function __construct(
        protected string $id,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query doc ($id: ID!) {
            document (id: $id) {
                file {
                    uuid,
                    name,
                    amountOfPages,
                    comment,
                    tags
                },
                date,
                comment,
                ... on InvoiceDocument {
                    type,
                    paymentState
                }
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
