<?php

namespace Clearfacts\Data;

class Journal
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $name = null,
        public readonly ?bool $creditNote = null,
        public readonly ?string $type = null,
        public readonly ?bool $default = null,
        public readonly ?string $lastDocumentNumber = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            creditNote: $data['creditNote'] ?? null,
            type: $data['type'] ?? null,
            default: $data['default'] ?? null,
            lastDocumentNumber: $data['lastDocumentNumber'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'creditNote' => $this->creditNote,
            'type' => $this->type,
            'default' => $this->default,
            'lastDocumentNumber' => $this->lastDocumentNumber,
        ];
    }
}
