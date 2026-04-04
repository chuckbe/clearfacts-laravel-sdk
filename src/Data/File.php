<?php

namespace Clearfacts\Data;

class File
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        public readonly ?string $uuid = null,
        public readonly ?string $name = null,
        public readonly ?int $amountOfPages = null,
        public readonly ?string $comment = null,
        public readonly array $tags = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'] ?? null,
            name: $data['name'] ?? null,
            amountOfPages: $data['amountOfPages'] ?? null,
            comment: $data['comment'] ?? null,
            tags: $data['tags'] ?? [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'amountOfPages' => $this->amountOfPages,
            'comment' => $this->comment,
            'tags' => $this->tags,
        ];
    }
}
