<?php

namespace Clearfacts\Tests\Unit\Requests;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Api\Requests\Document\UploadFileRequest;
use Clearfacts\Enums\InvoiceType;
use Clearfacts\Tests\TestCase;
use Saloon\Data\MultipartValue;

class MultipartGraphQLRequestTest extends TestCase
{
    public function test_upload_file_request_builds_multipart_body(): void
    {
        $request = new UploadFileRequest(
            vatNumber: 'BE0123456789',
            uploadFileName: 'invoice.pdf',
            invoiceType: InvoiceType::PURCHASE,
            uploadFileContent: 'fake-pdf-bytes',
        );

        $body = $request->body()->all();

        $this->assertCount(3, $body);

        // First part: GraphQL query
        $this->assertInstanceOf(MultipartValue::class, $body[0]);
        $this->assertSame('query', $body[0]->name);
        $queryValue = $body[0]->value;
        $this->assertIsString($queryValue);
        $this->assertStringContainsString('mutation upload', $queryValue);
        $this->assertStringContainsString('uploadFile', $queryValue);

        // Second part: variables
        $this->assertInstanceOf(MultipartValue::class, $body[1]);
        $this->assertSame('variables', $body[1]->name);
        $variablesValue = $body[1]->value;
        $this->assertIsString($variablesValue);
        $decoded = json_decode($variablesValue, true);
        $this->assertIsArray($decoded);
        $this->assertSame('BE0123456789', $decoded['vatNumber']);
        $this->assertSame('invoice.pdf', $decoded['fileName']);
        $this->assertSame('PURCHASE', $decoded['invoiceType']);

        // Third part: file
        $this->assertInstanceOf(MultipartValue::class, $body[2]);
        $this->assertSame('file', $body[2]->name);
        $this->assertSame('fake-pdf-bytes', $body[2]->value);
        $this->assertSame('invoice.pdf', $body[2]->filename);
    }

    public function test_upload_file_request_resolves_graphql_endpoint(): void
    {
        $request = new UploadFileRequest(
            vatNumber: 'BE0123456789',
            uploadFileName: 'test.pdf',
            invoiceType: InvoiceType::SALE,
            uploadFileContent: 'content',
        );

        $this->assertSame('/graphql', $request->resolveEndpoint());
    }

    public function test_upload_file_request_sends_multipart_content_type_with_boundary(): void
    {
        // Regression: connector defaults must not overwrite the multipart Content-Type
        // that HasMultipartBody installs (otherwise Clearfacts replies "Invalid JSON").
        $client = new ClearfactsApiClient('test-token');
        $request = new UploadFileRequest(
            vatNumber: 'BE0123456789',
            uploadFileName: 'invoice.pdf',
            invoiceType: InvoiceType::PURCHASE,
            uploadFileContent: 'fake-pdf-bytes',
        );

        $contentType = $client->createPendingRequest($request)->headers()->get('Content-Type');

        $this->assertIsString($contentType);
        $this->assertStringStartsWith('multipart/form-data; boundary=', $contentType);
    }
}
