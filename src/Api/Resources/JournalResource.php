<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Journal\GetJournalsRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\Journal;
use Saloon\Http\Connector;

class JournalResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get all journals for a specific administration.
     *
     * @return Journal[]
     */
    public function list(string $companyNumber): array
    {
        $response = $this->connector->send(new GetJournalsRequest($companyNumber));

        return array_map(
            fn (array $item) => Journal::fromArray($item),
            $this->extractData($response, 'journals'),
        );
    }
}
