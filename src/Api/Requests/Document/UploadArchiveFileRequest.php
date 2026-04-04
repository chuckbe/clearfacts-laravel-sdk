<?php

namespace Clearfacts\Api\Requests\Document;

use Clearfacts\Api\Requests\MultipartGraphQLRequest;
use Clearfacts\Enums\ArchiveType;

class UploadArchiveFileRequest extends MultipartGraphQLRequest
{
    public function __construct(
        protected string $vatNumber,
        protected string $uploadFileName,
        protected ArchiveType $type,
        protected string $category,
        protected string $uploadFileContent,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        mutation upload ($vatNumber: String!, $fileName: String!, $type: VariousType!, $category: String!) {
            uploadArchiveFile (vatnumber: $vatNumber, filename: $fileName, type: $type, category: $category) {
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
            'type' => $this->type->value,
            'category' => $this->category,
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
