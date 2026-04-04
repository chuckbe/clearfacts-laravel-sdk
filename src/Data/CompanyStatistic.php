<?php

namespace Clearfacts\Data;

class CompanyStatistic
{
    /**
     * @param StatisticItem[] $items
     */
    public function __construct(
        public readonly ?string $companyNumber = null,
        public readonly array $items = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            companyNumber: $data['companyNumber'] ?? null,
            items: array_map(
                fn (array $item) => StatisticItem::fromArray($item),
                $data['items'] ?? [],
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'companyNumber' => $this->companyNumber,
            'items' => array_map(fn (StatisticItem $i) => $i->toArray(), $this->items),
        ];
    }
}
