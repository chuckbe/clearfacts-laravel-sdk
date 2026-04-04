<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\Address;
use Clearfacts\Data\Administration;
use Clearfacts\Data\Country;
use Clearfacts\Data\Email;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class AdministrationResourceTest extends TestCase
{
    public function test_list_returns_array_of_administrations(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'administrations' => [
                        [
                            'name' => 'Acme Corp',
                            'companyNumber' => '0123456789',
                            'accountManager' => 'John Doe',
                            'emails' => [
                                ['type' => 'general', 'emailAddress' => 'info@acme.be'],
                            ],
                            'address' => [
                                'streetAddress' => 'Main St 1',
                                'country' => ['iso2' => 'BE', 'name' => 'Belgium'],
                            ],
                        ],
                        [
                            'name' => 'Beta BV',
                            'companyNumber' => '9876543210',
                            'accountManager' => null,
                            'emails' => [],
                            'address' => null,
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->administration->list();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Administration::class, $result[0]);
        $this->assertSame('Acme Corp', $result[0]->name);
        $this->assertSame('0123456789', $result[0]->companyNumber);
        $this->assertSame('John Doe', $result[0]->accountManager);
        $this->assertCount(1, $result[0]->emails);
        $this->assertInstanceOf(Email::class, $result[0]->emails[0]);
        $this->assertSame('info@acme.be', $result[0]->emails[0]->emailAddress);
        $this->assertInstanceOf(Address::class, $result[0]->address);
        $this->assertSame('Main St 1', $result[0]->address->streetAddress);
        $this->assertInstanceOf(Country::class, $result[0]->address->country);
        $this->assertSame('BE', $result[0]->address->country->iso2);

        $this->assertSame('Beta BV', $result[1]->name);
        $this->assertNull($result[1]->address);
    }

    public function test_get_returns_single_administration(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'administration' => [
                        'name' => 'Acme Corp',
                        'companyNumber' => '0123456789',
                        'accountManager' => 'John Doe',
                        'emails' => [],
                        'address' => [
                            'streetAddress' => 'Main St 1',
                            'country' => ['iso2' => 'BE', 'name' => 'Belgium'],
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->administration->get('0123456789');

        $this->assertInstanceOf(Administration::class, $result);
        $this->assertSame('Acme Corp', $result->name);
        $this->assertSame('0123456789', $result->companyNumber);
    }

    public function test_graphql_error_throws_runtime_exception(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'errors' => [
                    ['message' => 'Not authorized'],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('GraphQL error');

        $client->administration->list();
    }
}
