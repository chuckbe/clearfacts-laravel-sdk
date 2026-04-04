<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Document\GetDocumentRequest;
use Clearfacts\Api\Requests\Document\UploadArchiveFileRequest;
use Clearfacts\Api\Requests\Document\UploadFileRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\File;
use Clearfacts\Data\InvoiceDocument;
use Clearfacts\Enums\ArchiveType;
use Clearfacts\Enums\InvoiceType;
use Saloon\Http\Connector;

class DocumentResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get a document by its ID (returned after upload).
     * Uses inline fragment to fetch InvoiceDocument fields.
     */
    public function get(string $id): InvoiceDocument
    {
        $response = $this->connector->send(new GetDocumentRequest($id));

        return InvoiceDocument::fromArray(
            $this->extractData($response, 'document'),
        );
    }

    /**
     * Upload a document (invoice) to a specific administration's inbox.
     * The file is sent as multipart/form-data.
     *
     * Supported file types: PDF, JPEG images, XML (Billing3/UBL.BE/E-FFF).
     *
     * @param string $vatNumber The VAT/company number of the target administration
     * @param string $fileName The original file name (e.g., "invoice_001.pdf")
     * @param InvoiceType $invoiceType PURCHASE, SALE, or VARIOUS
     * @param string $fileContent The raw file content
     */
    public function upload(
        string $vatNumber,
        string $fileName,
        InvoiceType $invoiceType,
        string $fileContent,
    ): File {
        $response = $this->connector->send(new UploadFileRequest(
            vatNumber: $vatNumber,
            uploadFileName: $fileName,
            invoiceType: $invoiceType,
            uploadFileContent: $fileContent,
        ));

        return File::fromArray(
            $this->extractData($response, 'uploadFile'),
        );
    }

    /**
     * Upload a file to the archive (Various or Permanent) of a specific administration.
     * The file is sent as multipart/form-data.
     *
     * @param string $vatNumber The VAT/company number of the target administration
     * @param string $fileName The original file name
     * @param ArchiveType $type VARIOUS or PERMANENT
     * @param string $category The archive category ID (from archiveCategory->list())
     * @param string $fileContent The raw file content
     */
    public function uploadArchive(
        string $vatNumber,
        string $fileName,
        ArchiveType $type,
        string $category,
        string $fileContent,
    ): File {
        $response = $this->connector->send(new UploadArchiveFileRequest(
            vatNumber: $vatNumber,
            uploadFileName: $fileName,
            type: $type,
            category: $category,
            uploadFileContent: $fileContent,
        ));

        return File::fromArray(
            $this->extractData($response, 'uploadArchiveFile'),
        );
    }
}
