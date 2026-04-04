<?php

namespace Clearfacts\Data;

class Administration
{
    /**
     * @param Email[] $emails
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $companyType = null,
        public readonly ?string $companyNumber = null,
        public readonly ?string $companyNumberType = null,
        public readonly ?bool $vatLiable = null,
        public readonly ?Address $address = null,
        public readonly ?string $slug = null,
        public readonly ?string $accountManager = null,
        public readonly array $emails = [],
        public readonly ?string $language = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            companyType: $data['companyType'] ?? null,
            companyNumber: $data['companyNumber'] ?? null,
            companyNumberType: $data['companyNumberType'] ?? null,
            vatLiable: $data['vatLiable'] ?? null,
            address: isset($data['address']) ? Address::fromArray($data['address']) : null,
            slug: $data['slug'] ?? null,
            accountManager: $data['accountManager'] ?? null,
            emails: array_map(
                fn (array $email) => Email::fromArray($email),
                $data['emails'] ?? [],
            ),
            language: $data['language'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'companyType' => $this->companyType,
            'companyNumber' => $this->companyNumber,
            'companyNumberType' => $this->companyNumberType,
            'vatLiable' => $this->vatLiable,
            'address' => $this->address?->toArray(),
            'slug' => $this->slug,
            'accountManager' => $this->accountManager,
            'emails' => array_map(fn (Email $e) => $e->toArray(), $this->emails),
            'language' => $this->language,
        ];
    }
}
