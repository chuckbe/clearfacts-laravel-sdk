<?php

namespace Clearfacts\Api\Requests\Associate;

use Clearfacts\Api\Requests\GraphQLRequest;

class EditAssociateRequest extends GraphQLRequest
{
    /**
     * @param array<string, mixed> $fields Fields to update (firstName, lastName, email, type, active, language, sendActivationMail, associateGroups)
     */
    public function __construct(
        protected string $id,
        protected array $fields,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        mutation edit ($id: ID!, $associate: EditAssociateArgument!) {
            editAssociate (id: $id, associate: $associate) {
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

    protected function variables(): array
    {
        return [
            'id' => $this->id,
            'associate' => $this->fields,
        ];
    }
}
