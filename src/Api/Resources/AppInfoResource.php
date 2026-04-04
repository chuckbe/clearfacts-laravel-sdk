<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\AppInfo\UpdateAppInfoRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\AppInfo;
use Saloon\Http\Connector;

class AppInfoResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Update the app info (badge, icon, image, iframe) for a specific administration.
     *
     * @param array{text?: string, textColor?: string, color?: string}|null $badge
     * @param array{type?: string, color?: string}|null $icon
     */
    public function update(
        string $vatnumber,
        ?string $emailaddress = null,
        ?array $badge = null,
        ?array $icon = null,
        ?string $imageUrl = null,
        ?string $iFrameUrl = null,
    ): AppInfo {
        $response = $this->connector->send(new UpdateAppInfoRequest(
            vatnumber: $vatnumber,
            emailaddress: $emailaddress,
            badge: $badge,
            icon: $icon,
            imageUrl: $imageUrl,
            iFrameUrl: $iFrameUrl,
        ));

        return AppInfo::fromArray(
            $this->extractData($response, 'updateAppInfo'),
        );
    }
}
