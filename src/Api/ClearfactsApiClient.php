<?php

namespace Clearfacts\Api;

use Clearfacts\Api\Resources\AccountantResource;
use Clearfacts\Api\Resources\AdministrationResource;
use Clearfacts\Api\Resources\AppInfoResource;
use Clearfacts\Api\Resources\ArchiveCategoryResource;
use Clearfacts\Api\Resources\AssociateResource;
use Clearfacts\Api\Resources\CustomerResource;
use Clearfacts\Api\Resources\DocumentResource;
use Clearfacts\Api\Resources\JournalResource;
use Clearfacts\Api\Resources\StatisticResource;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class ClearfactsApiClient extends Connector
{
    public AdministrationResource $administration;

    public DocumentResource $document;

    public CustomerResource $customer;

    public AccountantResource $accountant;

    public AssociateResource $associate;

    public ArchiveCategoryResource $archiveCategory;

    public JournalResource $journal;

    public StatisticResource $statistic;

    public AppInfoResource $appInfo;

    public function __construct(
        protected string $accessToken,
        protected string $baseUrl = 'https://api.clearfacts.be',
    ) {
        $this->administration = new AdministrationResource($this);
        $this->document = new DocumentResource($this);
        $this->customer = new CustomerResource($this);
        $this->accountant = new AccountantResource($this);
        $this->associate = new AssociateResource($this);
        $this->archiveCategory = new ArchiveCategoryResource($this);
        $this->journal = new JournalResource($this);
        $this->statistic = new StatisticResource($this);
        $this->appInfo = new AppInfoResource($this);
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->accessToken);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return [
            'timeout' => config('clearfacts.timeout', 30),
        ];
    }
}
