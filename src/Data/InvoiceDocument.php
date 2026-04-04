<?php

namespace Clearfacts\Data;

class InvoiceDocument
{
    public function __construct(
        public readonly ?File $file = null,
        public readonly ?string $date = null,
        public readonly ?string $comment = null,
        public readonly ?string $type = null,
        public readonly ?string $paymentState = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            file: isset($data['file']) ? File::fromArray($data['file']) : null,
            date: $data['date'] ?? null,
            comment: $data['comment'] ?? null,
            type: $data['type'] ?? null,
            paymentState: $data['paymentState'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'file' => $this->file?->toArray(),
            'date' => $this->date,
            'comment' => $this->comment,
            'type' => $this->type,
            'paymentState' => $this->paymentState,
        ];
    }
}
