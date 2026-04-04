<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\Accountant;
use Clearfacts\Data\Address;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class AccountantResourceTest extends TestCase
{
    public function test_get_returns_accountant(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'accountant' => [
                        'name' => 'Boekhoudkantoor XYZ',
                        'companyNumber' => '0111222333',
                        'email' => 'info@xyz.be',
                        'address' => [
                            'streetAddress' => 'Kerkstraat 10',
                            'country' => ['iso2' => 'BE', 'name' => 'Belgium'],
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->accountant->get();

        $this->assertInstanceOf(Accountant::class, $result);
        $this->assertSame('Boekhoudkantoor XYZ', $result->name);
        $this->assertSame('0111222333', $result->companyNumber);
        $this->assertSame('info@xyz.be', $result->email);
        $this->assertInstanceOf(Address::class, $result->address);
        $this->assertSame('Kerkstraat 10', $result->address->streetAddress);
    }
}
