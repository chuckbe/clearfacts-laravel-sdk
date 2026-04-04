<?php

namespace Clearfacts\Data;

class Country
{
    public function __construct(
        public readonly ?string $iso2 = null,
        public readonly ?string $name = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iso2: $data['iso2'] ?? null,
            name: $data['name'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'iso2' => $this->iso2,
            'name' => $this->name,
        ];
    }
}
