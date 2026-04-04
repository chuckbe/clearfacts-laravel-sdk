<?php

namespace Clearfacts\Data;

class Address
{
    public function __construct(
        public readonly ?string $streetAddress = null,
        public readonly ?string $extendedAddress = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $locality = null,
        public readonly ?Country $country = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            streetAddress: $data['streetAddress'] ?? null,
            extendedAddress: $data['extendedAddress'] ?? null,
            postalCode: $data['postalCode'] ?? null,
            locality: $data['locality'] ?? null,
            country: isset($data['country']) ? Country::fromArray($data['country']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'streetAddress' => $this->streetAddress,
            'extendedAddress' => $this->extendedAddress,
            'postalCode' => $this->postalCode,
            'locality' => $this->locality,
            'country' => $this->country?->toArray(),
        ];
    }
}
