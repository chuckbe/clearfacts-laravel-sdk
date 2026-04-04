<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\CompanyStatistic;
use Clearfacts\Data\StatisticItem;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class StatisticResourceTest extends TestCase
{
    public function test_company_statistics_returns_array_of_statistics(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'getCompanyStatistics' => [
                        [
                            'companyNumber' => '0123456789',
                            'items' => [
                                ['period' => '2024-01', 'value' => 42],
                                ['period' => '2024-02', 'value' => 55],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->statistic->companyStatistics(
            type: 'AIR',
            startPeriod: '2024-01',
            endPeriod: '2024-12',
        );

        $this->assertCount(1, $result);
        $this->assertInstanceOf(CompanyStatistic::class, $result[0]);
        $this->assertSame('0123456789', $result[0]->companyNumber);
        $this->assertCount(2, $result[0]->items);
        $this->assertInstanceOf(StatisticItem::class, $result[0]->items[0]);
        $this->assertSame('2024-01', $result[0]->items[0]->period);
        $this->assertSame(42, $result[0]->items[0]->value);
    }
}
