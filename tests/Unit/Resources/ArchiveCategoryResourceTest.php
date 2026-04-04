<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\ArchiveCategories;
use Clearfacts\Data\Category;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class ArchiveCategoryResourceTest extends TestCase
{
    public function test_list_returns_archive_categories(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'archiveCategories' => [
                        'various' => [
                            ['id' => 'v1', 'name' => 'Contracts'],
                            ['id' => 'v2', 'name' => 'Receipts'],
                        ],
                        'permanent' => [
                            ['id' => 'p1', 'name' => 'Annual Reports'],
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->archiveCategory->list('BE0123456789');

        $this->assertInstanceOf(ArchiveCategories::class, $result);
        $this->assertCount(2, $result->various);
        $this->assertCount(1, $result->permanent);
        $this->assertInstanceOf(Category::class, $result->various[0]);
        $this->assertSame('Contracts', $result->various[0]->name);
        $this->assertSame('Annual Reports', $result->permanent[0]->name);
    }
}
