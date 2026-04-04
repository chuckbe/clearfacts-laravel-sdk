<?php

namespace Clearfacts\Data;

class AppInfo
{
    /**
     * @param array{text?: string, textColor?: string, color?: string}|null $badge
     * @param array{type?: string, color?: string}|null $icon
     */
    public function __construct(
        public readonly ?string $vatnumber = null,
        public readonly ?string $emailaddress = null,
        public readonly ?array $badge = null,
        public readonly ?array $icon = null,
        public readonly ?string $imageUrl = null,
        public readonly ?string $iFrameUrl = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            vatnumber: $data['vatnumber'] ?? null,
            emailaddress: $data['emailaddress'] ?? null,
            badge: $data['badge'] ?? null,
            icon: $data['icon'] ?? null,
            imageUrl: $data['imageUrl'] ?? null,
            iFrameUrl: $data['iFrameUrl'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'vatnumber' => $this->vatnumber,
            'emailaddress' => $this->emailaddress,
            'badge' => $this->badge,
            'icon' => $this->icon,
            'imageUrl' => $this->imageUrl,
            'iFrameUrl' => $this->iFrameUrl,
        ];
    }
}
