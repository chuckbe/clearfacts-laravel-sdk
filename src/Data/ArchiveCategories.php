<?php

namespace Clearfacts\Data;

class ArchiveCategories
{
    /**
     * @param Category[] $various
     * @param Category[] $permanent
     */
    public function __construct(
        public readonly array $various = [],
        public readonly array $permanent = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            various: array_map(
                fn (array $cat) => Category::fromArray($cat),
                $data['various'] ?? [],
            ),
            permanent: array_map(
                fn (array $cat) => Category::fromArray($cat),
                $data['permanent'] ?? [],
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'various' => array_map(fn (Category $c) => $c->toArray(), $this->various),
            'permanent' => array_map(fn (Category $c) => $c->toArray(), $this->permanent),
        ];
    }
}
