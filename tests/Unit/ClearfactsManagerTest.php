<?php

namespace Clearfacts\Tests\Unit;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\ClearfactsManager;
use Clearfacts\Tests\TestCase;

class ClearfactsManagerTest extends TestCase
{
    public function test_set_access_token_stores_token(): void
    {
        $manager = new ClearfactsManager();
        $manager->setAccessToken('test-token-123');

        $this->assertSame('test-token-123', $manager->getAccessToken());
    }

    public function test_get_access_token_returns_null_by_default(): void
    {
        $manager = new ClearfactsManager();

        $this->assertNull($manager->getAccessToken());
    }

    public function test_set_access_token_returns_self_for_fluent_api(): void
    {
        $manager = new ClearfactsManager();
        $result = $manager->setAccessToken('token');

        $this->assertSame($manager, $result);
    }

    public function test_api_returns_clearfacts_api_client(): void
    {
        $manager = new ClearfactsManager();
        $manager->setAccessToken('test-token');

        $client = $manager->api();

        $this->assertInstanceOf(ClearfactsApiClient::class, $client);
    }

    public function test_api_returns_same_client_instance_for_same_token(): void
    {
        $manager = new ClearfactsManager();
        $manager->setAccessToken('test-token');

        $client1 = $manager->api();
        $client2 = $manager->api();

        $this->assertSame($client1, $client2);
    }

    public function test_api_returns_new_client_after_token_change(): void
    {
        $manager = new ClearfactsManager();
        $manager->setAccessToken('token-1');
        $client1 = $manager->api();

        $manager->setAccessToken('token-2');
        $client2 = $manager->api();

        $this->assertNotSame($client1, $client2);
    }

    public function test_api_throws_when_no_token_set(): void
    {
        $manager = new ClearfactsManager();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No access token set');

        $manager->api();
    }

    public function test_facade_resolves_manager(): void
    {
        $manager = \Clearfacts\Facades\Clearfacts::getFacadeRoot();

        $this->assertInstanceOf(ClearfactsManager::class, $manager);
    }

    public function test_facade_is_singleton(): void
    {
        $manager1 = app(ClearfactsManager::class);
        $manager2 = app(ClearfactsManager::class);

        $this->assertSame($manager1, $manager2);
    }
}
