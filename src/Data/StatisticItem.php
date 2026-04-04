<?php

namespace Clearfacts\Data;

class StatisticItem
{
    public function __construct(
        public readonly ?string $period = null,
        public readonly ?int $value = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'] ?? null,
            value: $data['value'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'value' => $this->value,
        ];
    }
}
