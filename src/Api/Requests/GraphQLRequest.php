<?php

namespace Clearfacts\Api\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base class for all Clearfacts GraphQL requests.
 *
 * Provides a standardised way to send GraphQL queries and mutations
 * to the Clearfacts API endpoint.
 */
abstract class GraphQLRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return config('clearfacts.graphql_endpoint', '/graphql');
    }

    /**
     * The GraphQL query or mutation string.
     */
    abstract protected function graphqlQuery(): string;

    /**
     * Variables to pass to the GraphQL query/mutation.
     *
     * @return array<string, mixed>
     */
    protected function variables(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'query' => $this->graphqlQuery(),
        ];

        $variables = $this->variables();

        if (! empty($variables)) {
            $body['variables'] = $variables;
        }

        return $body;
    }
}
