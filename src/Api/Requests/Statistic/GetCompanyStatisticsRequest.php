<?php

namespace Clearfacts\Api\Requests\Statistic;

use Clearfacts\Api\Requests\GraphQLRequest;

class GetCompanyStatisticsRequest extends GraphQLRequest
{
    public function __construct(
        protected string $type,
        protected string $startPeriod,
        protected string $endPeriod,
        protected ?string $companyNumber = null,
        protected ?string $invoicetype = null,
        protected ?bool $worklist = null,
        protected ?int $offset = null,
    ) {
    }

    protected function graphqlQuery(): string
    {
        return <<<'GRAPHQL'
        query statistics ($type: String!, $startPeriod: Date!, $endPeriod: Date!, $companyNumber: String, $invoicetype: InvoiceType, $worklist: Boolean, $_offset: Int) {
            getCompanyStatistics (type: $type, startPeriod: $startPeriod, endPeriod: $endPeriod, companyNumber: $companyNumber, invoicetype: $invoicetype, worklist: $worklist, _offset: $_offset) {
                companyNumber,
                items {
                    period,
                    value
                }
            }
        }
        GRAPHQL;
    }

    protected function variables(): array
    {
        return array_filter([
            'type' => $this->type,
            'startPeriod' => $this->startPeriod,
            'endPeriod' => $this->endPeriod,
            'companyNumber' => $this->companyNumber,
            'invoicetype' => $this->invoicetype,
            'worklist' => $this->worklist,
            '_offset' => $this->offset,
        ], fn ($value) => $value !== null);
    }
}
