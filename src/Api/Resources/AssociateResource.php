<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Associate\AddAssociateRequest;
use Clearfacts\Api\Requests\Associate\EditAssociateRequest;
use Clearfacts\Api\Requests\Associate\GetAssociateGroupsRequest;
use Clearfacts\Api\Requests\Associate\GetAssociatesRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\Associate;
use Clearfacts\Data\AssociateGroup;
use Clearfacts\Enums\AssociateType;
use Clearfacts\Enums\Language;
use Saloon\Http\Connector;

class AssociateResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get all associates for the accountant.
     *
     * @return Associate[]
     */
    public function list(): array
    {
        $response = $this->connector->send(new GetAssociatesRequest());

        return array_map(
            fn (array $item) => Associate::fromArray($item),
            $this->extractData($response, 'associates'),
        );
    }

    /**
     * Get all associate groups for the accountant.
     *
     * @return AssociateGroup[]
     */
    public function groups(): array
    {
        $response = $this->connector->send(new GetAssociateGroupsRequest());

        return array_map(
            fn (array $item) => AssociateGroup::fromArray($item),
            $this->extractData($response, 'associateGroups'),
        );
    }

    /**
     * Add a new associate to the accountant.
     * The returned Associate will include the plainPassword (one-time only).
     *
     * @param array<array{id: string}> $associateGroups
     */
    public function add(
        string $firstName,
        string $lastName,
        string $email,
        AssociateType $type,
        bool $active,
        Language $language,
        bool $sendActivationMail,
        array $associateGroups = [],
    ): Associate {
        $response = $this->connector->send(new AddAssociateRequest(
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
            type: $type,
            active: $active,
            language: $language,
            sendActivationMail: $sendActivationMail,
            associateGroups: $associateGroups,
        ));

        return Associate::fromArray(
            $this->extractData($response, 'addAssociate'),
        );
    }

    /**
     * Edit an existing associate. Only pass the fields you want to update.
     *
     * @param array<string, mixed> $fields e.g. ['firstName' => 'Updated', 'active' => false]
     */
    public function edit(string $id, array $fields): Associate
    {
        $response = $this->connector->send(new EditAssociateRequest($id, $fields));

        return Associate::fromArray(
            $this->extractData($response, 'editAssociate'),
        );
    }
}
