<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\AppInfo;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class AppInfoResourceTest extends TestCase
{
    public function test_update_returns_app_info(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'updateAppInfo' => [
                        'vatnumber' => 'BE0123456789',
                        'emailaddress' => 'app@example.be',
                        'badge' => [
                            'text' => '3',
                            'textColor' => '#ffffff',
                            'color' => '#ff0000',
                        ],
                        'icon' => [
                            'type' => 'check',
                            'color' => '#00ff00',
                        ],
                        'imageUrl' => 'https://example.be/logo.png',
                        'iFrameUrl' => 'https://example.be/embed',
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->appInfo->update(
            vatnumber: 'BE0123456789',
            emailaddress: 'app@example.be',
            badge: ['text' => '3', 'textColor' => '#ffffff', 'color' => '#ff0000'],
            icon: ['type' => 'check', 'color' => '#00ff00'],
        );

        $this->assertInstanceOf(AppInfo::class, $result);
        $this->assertSame('BE0123456789', $result->vatnumber);
        $this->assertSame('app@example.be', $result->emailaddress);
        $this->assertSame('3', $result->badge['text']);
        $this->assertSame('check', $result->icon['type']);
        $this->assertSame('https://example.be/logo.png', $result->imageUrl);
        $this->assertSame('https://example.be/embed', $result->iFrameUrl);
    }
}
