<?php

namespace Clearfacts\Data;

class BusinessPartner
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $companyNumber = null,
        public readonly ?string $name = null,
        public readonly ?Address $address = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $fax = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            companyNumber: $data['companyNumber'] ?? null,
            name: $data['name'] ?? null,
            address: isset($data['address']) ? Address::fromArray($data['address']) : null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            fax: $data['fax'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'companyNumber' => $this->companyNumber,
            'name' => $this->name,
            'address' => $this->address?->toArray(),
            'email' => $this->email,
            'phone' => $this->phone,
            'fax' => $this->fax,
        ];
    }
}
