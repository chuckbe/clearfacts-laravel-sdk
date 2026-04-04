<?php

namespace Clearfacts\Data;

class Email
{
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?string $emailAddress = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'] ?? null,
            emailAddress: $data['emailAddress'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'emailAddress' => $this->emailAddress,
        ];
    }
}
