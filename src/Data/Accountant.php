<?php

namespace Clearfacts\Data;

class Accountant
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $companyNumber = null,
        public readonly ?string $systemName = null,
        public readonly ?Address $address = null,
        public readonly ?string $platformName = null,
        public readonly ?string $email = null,
        public readonly ?string $logo = null,
        public readonly ?string $favicon = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            companyNumber: $data['companyNumber'] ?? null,
            systemName: $data['systemName'] ?? null,
            address: isset($data['address']) ? Address::fromArray($data['address']) : null,
            platformName: $data['platformName'] ?? null,
            email: $data['email'] ?? null,
            logo: $data['logo'] ?? null,
            favicon: $data['favicon'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'companyNumber' => $this->companyNumber,
            'systemName' => $this->systemName,
            'address' => $this->address?->toArray(),
            'platformName' => $this->platformName,
            'email' => $this->email,
            'logo' => $this->logo,
            'favicon' => $this->favicon,
        ];
    }
}
