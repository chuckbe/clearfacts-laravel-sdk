<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\Address;
use Clearfacts\Data\BusinessPartner;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class CustomerResourceTest extends TestCase
{
    public function test_list_returns_array_of_business_partners(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'customers' => [
                        [
                            'id' => '1',
                            'companyNumber' => '0999888777',
                            'name' => 'Customer One',
                            'address' => [
                                'streetAddress' => 'Rue de la Loi 1',
                                'extendedAddress' => null,
                                'postalCode' => '1000',
                                'locality' => 'Brussels',
                                'country' => ['iso2' => 'BE', 'name' => 'Belgium'],
                            ],
                            'email' => 'one@customer.be',
                            'phone' => '+32 2 123 4567',
                            'fax' => null,
                        ],
                        [
                            'id' => '2',
                            'companyNumber' => '0111222333',
                            'name' => 'Customer Two',
                            'address' => null,
                            'email' => null,
                            'phone' => null,
                            'fax' => null,
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->customer->list('0123456789');

        $this->assertCount(2, $result);
        $this->assertInstanceOf(BusinessPartner::class, $result[0]);
        $this->assertSame('Customer One', $result[0]->name);
        $this->assertSame('0999888777', $result[0]->companyNumber);
        $this->assertInstanceOf(Address::class, $result[0]->address);
        $this->assertSame('1000', $result[0]->address->postalCode);
        $this->assertSame('Brussels', $result[0]->address->locality);
        $this->assertSame('one@customer.be', $result[0]->email);

        $this->assertSame('Customer Two', $result[1]->name);
        $this->assertNull($result[1]->address);
    }

    public function test_list_with_offset(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'customers' => [],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->customer->list('0123456789', offset: 100);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
