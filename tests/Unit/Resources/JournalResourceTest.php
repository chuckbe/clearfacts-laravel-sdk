<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\Journal;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class JournalResourceTest extends TestCase
{
    public function test_list_returns_array_of_journals(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'journals' => [
                        [
                            'id' => 'j1',
                            'name' => 'AK',
                            'creditNote' => false,
                            'type' => 'PURCHASE',
                            'default' => true,
                            'lastDocumentNumber' => '2024/001',
                        ],
                        [
                            'id' => 'j2',
                            'name' => 'VK',
                            'creditNote' => true,
                            'type' => 'SALE',
                            'default' => false,
                            'lastDocumentNumber' => null,
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->journal->list('0123456789');

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Journal::class, $result[0]);
        $this->assertSame('AK', $result[0]->name);
        $this->assertFalse($result[0]->creditNote);
        $this->assertSame('PURCHASE', $result[0]->type);
        $this->assertTrue($result[0]->default);
        $this->assertSame('2024/001', $result[0]->lastDocumentNumber);

        $this->assertTrue($result[1]->creditNote);
        $this->assertNull($result[1]->lastDocumentNumber);
    }
}
