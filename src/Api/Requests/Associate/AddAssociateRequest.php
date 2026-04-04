<?php

namespace Clearfacts\Api\Requests\Associate;

use Clearfacts\Api\Requests\GraphQLRequest;
use Clearfacts\Enums\AssociateType;
use Clearfacts\Enums\Language;

class AddAssociateRequest extends GraphQLRequest
{
    /**
     * @param array<array{id: string}> $associateGroups
     */
    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected string $email,
        protected AssociateType $type,
        protected bool $active,
        protected Language $language,
        protected bool $sendActivationMail,
        protected array $associateGroups = [],
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        mutation add ($associate: AddAssociateArgument!) {
            addAssociate (associate: $associate) {
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
                },
                plainPassword
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        $associate = [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'type' => $this->type->value,
            'active' => $this->active,
            'language' => $this->language->value,
            'sendActivationMail' => $this->sendActivationMail,
        ];

        if (! empty($this->associateGroups)) {
            $associate['associateGroups'] = $this->associateGroups;
        }

        return [
            'associate' => $associate,
        ];
    }
}
