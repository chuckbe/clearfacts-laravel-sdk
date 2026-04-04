<?php

namespace Clearfacts\Data;

class Associate
{
    /**
     * @param AssociateGroup[] $associateGroups
     */
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
        public readonly ?string $language = null,
        public readonly ?string $type = null,
        public readonly ?bool $active = null,
        public readonly array $associateGroups = [],
        public readonly ?string $plainPassword = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            firstName: $data['firstName'] ?? null,
            lastName: $data['lastName'] ?? null,
            email: $data['email'] ?? null,
            language: $data['language'] ?? null,
            type: $data['type'] ?? null,
            active: $data['active'] ?? null,
            associateGroups: array_map(
                fn (array $group) => AssociateGroup::fromArray($group),
                $data['associateGroups'] ?? [],
            ),
            plainPassword: $data['plainPassword'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'language' => $this->language,
            'type' => $this->type,
            'active' => $this->active,
            'associateGroups' => array_map(fn (AssociateGroup $g) => $g->toArray(), $this->associateGroups),
            'plainPassword' => $this->plainPassword,
        ];
    }
}
