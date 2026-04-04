<?php

namespace Clearfacts\Api\Requests\AppInfo;

use Clearfacts\Api\Requests\GraphQLRequest;

class UpdateAppInfoRequest extends GraphQLRequest
{
    /**
     * @param array{text?: string, textColor?: string, color?: string}|null $badge
     * @param array{type?: string, color?: string}|null $icon
     */
    public function __construct(
        protected string $vatnumber,
        protected ?string $emailaddress = null,
        protected ?array $badge = null,
        protected ?array $icon = null,
        protected ?string $imageUrl = null,
        protected ?string $iFrameUrl = null,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        mutation update ($vatnumber: String!, $emailaddress: String, $badge: AppBadgeArgument, $icon: AppIconArgument, $imageUrl: String, $iFrameUrl: String) {
            updateAppInfo (vatnumber: $vatnumber, emailaddress: $emailaddress, badge: $badge, icon: $icon, imageUrl: $imageUrl, iFrameUrl: $iFrameUrl) {
                vatnumber,
                emailaddress,
                badge {
                    text,
                    textColor,
                    color
                },
                icon {
                    type,
                    color
                },
                imageUrl,
                iFrameUrl
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return array_filter([
            'vatnumber' => $this->vatnumber,
            'emailaddress' => $this->emailaddress,
            'badge' => $this->badge,
            'icon' => $this->icon,
            'imageUrl' => $this->imageUrl,
            'iFrameUrl' => $this->iFrameUrl,
        ], fn ($value) => $value !== null);
    }
}
