<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\File;
use Clearfacts\Data\InvoiceDocument;
use Clearfacts\Enums\ArchiveType;
use Clearfacts\Enums\InvoiceType;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class DocumentResourceTest extends TestCase
{
    public function test_get_returns_invoice_document(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'document' => [
                        'file' => [
                            'uuid' => 'abc-123',
                            'name' => 'invoice.pdf',
                            'amountOfPages' => 2,
                            'comment' => 'Test comment',
                            'tags' => ['purchase', 'Q1'],
                        ],
                        'date' => '2024-01-15',
                        'comment' => 'Invoice comment',
                        'type' => 'PURCHASE',
                        'paymentState' => 'UNPAID',
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->document->get('abc-123');

        $this->assertInstanceOf(InvoiceDocument::class, $result);
        $this->assertInstanceOf(File::class, $result->file);
        $this->assertSame('abc-123', $result->file->uuid);
        $this->assertSame('invoice.pdf', $result->file->name);
        $this->assertSame(2, $result->file->amountOfPages);
        $this->assertSame(['purchase', 'Q1'], $result->file->tags);
        $this->assertSame('2024-01-15', $result->date);
        $this->assertSame('PURCHASE', $result->type);
        $this->assertSame('UNPAID', $result->paymentState);
    }

    public function test_upload_returns_file(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'uploadFile' => [
                        'uuid' => 'new-uuid-456',
                        'amountOfPages' => 3,
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->document->upload(
            vatNumber: 'BE0123456789',
            fileName: 'invoice.pdf',
            invoiceType: InvoiceType::PURCHASE,
            fileContent: 'fake-pdf-content',
        );

        $this->assertInstanceOf(File::class, $result);
        $this->assertSame('new-uuid-456', $result->uuid);
        $this->assertSame(3, $result->amountOfPages);
    }

    public function test_upload_archive_returns_file(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'uploadArchiveFile' => [
                        'uuid' => 'archive-uuid-789',
                        'amountOfPages' => 1,
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->document->uploadArchive(
            vatNumber: 'BE0123456789',
            fileName: 'contract.pdf',
            type: ArchiveType::PERMANENT,
            category: 'cat-id-123',
            fileContent: 'fake-pdf-content',
        );

        $this->assertInstanceOf(File::class, $result);
        $this->assertSame('archive-uuid-789', $result->uuid);
        $this->assertSame(1, $result->amountOfPages);
    }
}
