<?php

namespace Clearfacts\Api\Resources;

use Clearfacts\Api\Requests\Statistic\GetCompanyStatisticsRequest;
use Clearfacts\Concerns\ExtractsGraphQLData;
use Clearfacts\Data\CompanyStatistic;
use Saloon\Http\Connector;

class StatisticResource
{
    use ExtractsGraphQLData;

    public function __construct(
        protected Connector $connector,
    ) {
    }

    /**
     * Get company statistics (AIR or processing) for a given period.
     * Results are paginated. Use offset for paging.
     *
     * @return CompanyStatistic[]
     */
    public function companyStatistics(
        string $type,
        string $startPeriod,
        string $endPeriod,
        ?string $companyNumber = null,
        ?string $invoicetype = null,
        ?bool $worklist = null,
        ?int $offset = null,
    ): array {
        $response = $this->connector->send(new GetCompanyStatisticsRequest(
            type: $type,
            startPeriod: $startPeriod,
            endPeriod: $endPeriod,
            companyNumber: $companyNumber,
            invoicetype: $invoicetype,
            worklist: $worklist,
            offset: $offset,
        ));

        return array_map(
            fn (array $item) => CompanyStatistic::fromArray($item),
            $this->extractData($response, 'getCompanyStatistics'),
        );
    }
}
