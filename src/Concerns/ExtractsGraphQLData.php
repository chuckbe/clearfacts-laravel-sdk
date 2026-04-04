<?php

namespace Clearfacts\Concerns;

use Saloon\Http\Response;

trait ExtractsGraphQLData
{
    /**
     * Extract the data for a given key from a GraphQL response.
     * Throws if the response contains GraphQL errors.
     *
     * @return array<string, mixed>
     */
    protected function extractData(Response $response, string $key): array
    {
        $data = $response->json();

        if (isset($data['errors'])) {
            throw new \RuntimeException(
                'GraphQL error: '.json_encode($data['errors']),
            );
        }

        return $data['data'][$key] ?? [];
    }
}
