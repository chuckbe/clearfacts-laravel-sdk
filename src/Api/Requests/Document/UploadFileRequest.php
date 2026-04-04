<?php

namespace Clearfacts\Api\Requests\Document;

use Clearfacts\Api\Requests\MultipartGraphQLRequest;
use Clearfacts\Enums\InvoiceType;

class UploadFileRequest extends MultipartGraphQLRequest
{
    public function __construct(
        protected string $vatNumber,
        protected string $uploadFileName,
        protected InvoiceType $invoiceType,
        protected string $uploadFileContent,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        mutation upload ($vatNumber: String!, $fileName: String!, $invoiceType: InvoiceTypeArgument!) {
            uploadFile (vatnumber: $vatNumber, filename: $fileName, invoicetype: $invoiceType) {
                uuid,
                amountOfPages
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return [
            'vatNumber' => $this->vatNumber,
            'fileName' => $this->uploadFileName,
            'invoiceType' => $this->invoiceType->value,
        ];
    }

    protected function fileContent(): string
    {
        return $this->uploadFileContent;
    }

    protected function fileName(): string
    {
        return $this->uploadFileName;
    }
}
