<?php

namespace Clearfacts\Api\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

/**
 * Base class for Clearfacts GraphQL mutations that require file uploads.
 *
 * File uploads are sent as multipart/form-data with the query and variables
 * as text fields and the file as a file attachment named "file".
 */
abstract class MultipartGraphQLRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return config('clearfacts.graphql_endpoint', '/graphql');
    }

    /**
     * The GraphQL mutation string.
     */
    abstract protected function graphqlQuery(): string;

    /**
     * Variables to pass to the GraphQL mutation.
     *
     * @return array<string, mixed>
     */
    protected function variables(): array
    {
        return [];
    }

    /**
     * The raw file content to upload.
     */
    abstract protected function fileContent(): string;

    /**
     * The file name for the upload.
     */
    abstract protected function fileName(): string;

    /**
     * @return MultipartValue[]
     */
    protected function defaultBody(): array
    {
        $parts = [
            new MultipartValue('query', $this->graphqlQuery()),
        ];

        $variables = $this->variables();

        if (! empty($variables)) {
            $parts[] = new MultipartValue('variables', json_encode($variables, JSON_THROW_ON_ERROR));
        }

        $parts[] = new MultipartValue(
            name: 'file',
            value: $this->fileContent(),
            filename: $this->fileName(),
        );

        return $parts;
    }
}
