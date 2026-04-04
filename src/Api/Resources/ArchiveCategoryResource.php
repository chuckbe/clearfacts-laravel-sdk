<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\ArchiveCategory\GetArchiveCategoriesRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\ArchiveCategories;
use Saloon\Http\Connector;

class ArchiveCategoryResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get all archive categories (various and permanent) for a specific administration.
     */
    public function list(string $vatNumber): ArchiveCategories
    {
        $response = $this->connector->send(new GetArchiveCategoriesRequest($vatNumber));

        return ArchiveCategories::fromArray(
            $this->extractData($response, 'archiveCategories'),
        );
    }
}
