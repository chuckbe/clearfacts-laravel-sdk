# Clearfacts Laravel SDK

A Laravel SDK for the [Clearfacts](https://www.clearfacts.be/) pre-accounting platform API. Built on [Saloon PHP](https://docs.saloon.dev/) with a fluent, resource-based interface for the Clearfacts GraphQL API.

## Requirements

- PHP 8.2+
- Laravel 11, 12 or 13
- Saloon 4 (`saloonphp/saloon` ^4.0)

## Installation

```bash
composer require chuckbe/clearfacts-laravel-sdk
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=clearfacts-config
```

## Configuration

Add the following to your `.env` file:

```env
CLEARFACTS_API_URL=https://api.clearfacts.be
CLEARFACTS_CLIENT_ID=your-client-id
CLEARFACTS_CLIENT_SECRET=your-client-secret
CLEARFACTS_REDIRECT_URI=https://your-app.com/auth/clearfacts/callback
CLEARFACTS_ISSUER=https://login.clearfacts.be
```

See `config/clearfacts.php` for all available options (timeouts, retry behaviour, scopes).

## Authentication (Multi-Tenant / SaaS)

Clearfacts uses **OpenID Connect** (Authorization Code Flow). This SDK does **not** handle the OIDC flow itself. Instead, use [Laravel Socialite](https://laravel.com/docs/socialite) with a Clearfacts provider (or a generic OIDC Socialite driver) to obtain access tokens per tenant.

The OIDC discovery endpoint is at `https://login.clearfacts.be/.well-known/openid-configuration` (or `https://<accountant-slug>.clearfacts.be` for accountant-specific endpoints).

The configured scopes are: `openid`, `email`, `profile`, `accountant`, `statistics`, `read_administrations`, `associate_read`, `associate_actions`, `journal_read`, `contact_read`, `upload_document`, `archive_read`, `archive_actions`.

Once you have an access token for a tenant, set it before making API calls:

```php
use Clearfacts\Facades\Clearfacts;

// Set the token (typically in middleware or a tenant-aware service)
Clearfacts::setAccessToken($tenant->clearfacts_access_token);
```

## Usage

The SDK provides a fluent, resource-based API:

```php
Clearfacts::api()->resource->method($args);
```

### Administrations

```php
use Clearfacts\Facades\Clearfacts;

// List all administrations
$administrations = Clearfacts::api()->administration->list();

// Get a single administration by company number
$admin = Clearfacts::api()->administration->get('0123456789');

echo $admin->name;
echo $admin->companyNumber;
echo $admin->companyType;
echo $admin->accountManager;
echo $admin->address->locality;
echo $admin->language;
```

### Documents

```php
use Clearfacts\Facades\Clearfacts;
use Clearfacts\Enums\InvoiceType;
use Clearfacts\Enums\ArchiveType;

// Upload a purchase invoice to an administration's inbox
$file = Clearfacts::api()->document->upload(
    vatNumber: 'BE0123456789',
    fileName: 'invoice_001.pdf',
    invoiceType: InvoiceType::PURCHASE,
    fileContent: file_get_contents('/path/to/invoice.pdf'),
);

echo $file->uuid;
echo $file->name;
echo $file->amountOfPages;

// Upload to the archive (various or permanent)
$file = Clearfacts::api()->document->uploadArchive(
    vatNumber: 'BE0123456789',
    fileName: 'contract.pdf',
    type: ArchiveType::PERMANENT,
    category: 'category-id-from-archiveCategory-list',
    fileContent: file_get_contents('/path/to/contract.pdf'),
);

// Get a document by ID (returned after upload)
$doc = Clearfacts::api()->document->get('document-uuid');

echo $doc->type;          // InvoiceType (PURCHASE, SALE, VARIOUS)
echo $doc->paymentState;  // PAID or UNPAID
echo $doc->file->uuid;
```

**Invoice types:** `PURCHASE`, `SALE`, `VARIOUS`

**Archive types:** `VARIOUS`, `PERMANENT`

**Supported upload formats:** PDF, JPEG images, XML (Billing3/UBL.BE/E-FFF)

### Customers (Business Partners)

```php
use Clearfacts\Facades\Clearfacts;

// Get all customers for an administration (paginated, max 100 per request)
$customers = Clearfacts::api()->customer->list('0123456789');

foreach ($customers as $customer) {
    echo $customer->name;
    echo $customer->companyNumber;
    echo $customer->email;
    echo $customer->phone;
    echo $customer->address->locality;
}

// Paginate with offset
$page2 = Clearfacts::api()->customer->list('0123456789', offset: 100);
```

### Journals

```php
use Clearfacts\Facades\Clearfacts;

// Get all journals for an administration
$journals = Clearfacts::api()->journal->list('0123456789');

foreach ($journals as $journal) {
    echo $journal->id;
    echo $journal->name;
    echo $journal->type;                // e.g. PURCHASE, SALE, VARIOUS
    echo $journal->creditNote;          // bool
    echo $journal->default;             // bool
    echo $journal->lastDocumentNumber;
}
```

### Archive Categories

```php
use Clearfacts\Facades\Clearfacts;

// Get archive categories for an administration
$categories = Clearfacts::api()->archiveCategory->list('BE0123456789');

// Various categories
foreach ($categories->various as $category) {
    echo $category->id . ': ' . $category->name;
}

// Permanent categories
foreach ($categories->permanent as $category) {
    echo $category->id . ': ' . $category->name;
}
```

### Accountant

```php
use Clearfacts\Facades\Clearfacts;

// Get the accountant linked to the authenticated user
$accountant = Clearfacts::api()->accountant->get();

echo $accountant->name;
echo $accountant->companyNumber;
echo $accountant->email;
echo $accountant->platformName;
echo $accountant->address->locality;
```

### Associates

```php
use Clearfacts\Facades\Clearfacts;
use Clearfacts\Enums\AssociateType;
use Clearfacts\Enums\Language;

// List all associates
$associates = Clearfacts::api()->associate->list();

foreach ($associates as $associate) {
    echo $associate->firstName . ' ' . $associate->lastName;
    echo $associate->email;
    echo $associate->type;     // ADMIN, ASSOCIATE, SUPPORT
    echo $associate->language;
    echo $associate->active ? 'Active' : 'Inactive';
}

// List associate groups
$groups = Clearfacts::api()->associate->groups();

// Add a new associate
$associate = Clearfacts::api()->associate->add(
    firstName: 'Jane',
    lastName: 'Doe',
    email: 'jane@example.com',
    type: AssociateType::ASSOCIATE,
    active: true,
    language: Language::DUTCH,
    sendActivationMail: true,
    associateGroups: [['id' => 'group-uuid']],
);

// The plain password is only available once after creation
echo $associate->plainPassword;

// Edit an associate (only pass the fields you want to update)
$associate = Clearfacts::api()->associate->edit(
    id: $associate->id,
    fields: ['firstName' => 'Updated', 'active' => false],
);
```

### Statistics

```php
use Clearfacts\Facades\Clearfacts;

// Get company statistics (AIR or processing) for a period
$stats = Clearfacts::api()->statistic->companyStatistics(
    type: 'AIR',                   // or 'processing'
    startPeriod: '2024-01-01',
    endPeriod: '2024-12-31',
    companyNumber: '0123456789',   // optional: filter by company
    invoicetype: 'PURCHASE',       // optional: filter by invoice type
);

foreach ($stats as $companyStat) {
    echo $companyStat->companyNumber;
    foreach ($companyStat->items as $item) {
        echo $item->period . ': ' . $item->value;
    }
}
```

### App Info

```php
use Clearfacts\Facades\Clearfacts;

// Update the app info for a specific administration
$appInfo = Clearfacts::api()->appInfo->update(
    vatnumber: 'BE0123456789',
    emailaddress: 'notify@example.com',
    badge: ['text' => '3', 'textColor' => '#FFFFFF', 'color' => '#FF0000'],
    icon: ['type' => 'warning', 'color' => '#FFA500'],
    imageUrl: 'https://example.com/logo.png',
    iFrameUrl: 'https://example.com/embed',
);
```

## API Reference

### Available Resources

| Resource | Method | Description |
|---|---|---|
| `administration` | `list()` | List all administrations |
| `administration` | `get(companyNumber)` | Get a single administration |
| `document` | `get(id)` | Get a document by ID |
| `document` | `upload(vatNumber, fileName, invoiceType, fileContent)` | Upload invoice to inbox |
| `document` | `uploadArchive(vatNumber, fileName, type, category, fileContent)` | Upload file to archive |
| `customer` | `list(companyNumber, ?offset)` | List customers/business partners (paginated) |
| `journal` | `list(companyNumber)` | List journals for an administration |
| `archiveCategory` | `list(vatNumber)` | Get archive categories (various + permanent) |
| `accountant` | `get()` | Get the linked accountant |
| `associate` | `list()` | List all associates |
| `associate` | `groups()` | List associate groups |
| `associate` | `add(firstName, lastName, email, type, active, language, sendActivationMail, ?associateGroups)` | Add a new associate |
| `associate` | `edit(id, fields)` | Edit an existing associate |
| `statistic` | `companyStatistics(type, startPeriod, endPeriod, ...)` | Get company statistics |
| `appInfo` | `update(vatnumber, ...)` | Update app info for an administration |

### Data Classes

All API responses are mapped to typed data classes in `Clearfacts\Data\`:

| Class | Fields |
|---|---|
| `Administration` | name, companyType, companyNumber, companyNumberType, vatLiable, address, slug, accountManager, emails[], language |
| `Accountant` | name, companyNumber, systemName, address, platformName, email, logo, favicon |
| `Associate` | id, firstName, lastName, email, language, type, active, associateGroups[], plainPassword |
| `AssociateGroup` | id, name |
| `BusinessPartner` | id, companyNumber, name, address, email, phone, fax |
| `InvoiceDocument` | file, date, comment, type, paymentState |
| `File` | uuid, name, amountOfPages, comment, tags[] |
| `Journal` | id, name, creditNote, type, default, lastDocumentNumber |
| `CompanyStatistic` | companyNumber, items[] |
| `StatisticItem` | period, value |
| `AppInfo` | vatnumber, emailaddress, badge, icon, imageUrl, iFrameUrl |
| `ArchiveCategories` | various[], permanent[] |
| `Category` | id, name |
| `Address` | streetAddress, extendedAddress, postalCode, locality, country |
| `Country` | iso2, name |
| `Email` | type, emailAddress |

### Enums

| Enum | Values |
|---|---|
| `InvoiceType` | PURCHASE, SALE, VARIOUS |
| `ArchiveType` | VARIOUS, PERMANENT |
| `AssociateType` | ADMIN, ASSOCIATE, SUPPORT |
| `Language` | DUTCH (nl_BE), FRENCH (fr_BE), ENGLISH (en_BE), GERMAN (de_BE) |
| `PaymentState` | PAID, UNPAID |

## Architecture

```
config/
  clearfacts.php                # Configuration file
src/
  Api/
    ClearfactsApiClient.php     # Saloon Connector (entry point for all API calls)
    Requests/                   # GraphQL request classes per domain
      Administration/
        GetAdministrationsRequest.php
        GetAdministrationRequest.php
      Accountant/
        GetAccountantRequest.php
      AppInfo/
        UpdateAppInfoRequest.php
      ArchiveCategory/
        GetArchiveCategoriesRequest.php
      Associate/
        GetAssociatesRequest.php
        GetAssociateGroupsRequest.php
        AddAssociateRequest.php
        EditAssociateRequest.php
      Customer/
        GetCustomersRequest.php
      Document/
        GetDocumentRequest.php
        UploadFileRequest.php
        UploadArchiveFileRequest.php
      Journal/
        GetJournalsRequest.php
      Statistic/
        GetCompanyStatisticsRequest.php
      GraphQLRequest.php            # Base class for JSON GraphQL requests
      MultipartGraphQLRequest.php   # Base class for multipart file uploads
    Resources/                  # Fluent resource classes
      AdministrationResource.php
      AccountantResource.php
      AppInfoResource.php
      ArchiveCategoryResource.php
      AssociateResource.php
      CustomerResource.php
      DocumentResource.php
      JournalResource.php
      StatisticResource.php
  Concerns/
    ExtractsGraphQLData.php     # Shared trait for GraphQL response extraction
  Data/                         # Typed data/DTO classes
  Enums/                        # PHP 8.2+ backed enums
  Facades/
    Clearfacts.php              # Laravel Facade
  ClearfactsManager.php         # Manager (handles token, creates API client)
  ClearfactsServiceProvider.php # Service provider with auto-discovery
```

## Multi-Tenant Setup Example

In a typical multi-tenant SaaS application, you might set the token in middleware:

```php
// app/Http/Middleware/SetClearfactsToken.php
namespace App\Http\Middleware;

use Clearfacts\Facades\Clearfacts;
use Closure;

class SetClearfactsToken
{
    public function handle($request, Closure $next)
    {
        $tenant = $request->user()->tenant;

        if ($tenant->clearfacts_access_token) {
            Clearfacts::setAccessToken($tenant->clearfacts_access_token);
        }

        return $next($request);
    }
}
```

## Development

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/phpunit

# Run static analysis (level 8)
vendor/bin/phpstan analyse

# Run code style fixer (PSR-12)
vendor/bin/php-cs-fixer fix --config=php-cs-fixer.php

# Run all checks at once (also runs automatically on commit via GrumPHP)
vendor/bin/grumphp run
```

### Tests

Tests use [Orchestra Testbench](https://packages.tools/testbench) and [Saloon's MockClient](https://docs.saloon.dev/testing/recording-requests) to mock HTTP responses without hitting the real API.

```
tests/
  TestCase.php                              # Base test case (loads service provider + facade)
  Unit/
    ClearfactsManagerTest.php               # Manager: token management, client creation, facade
    Requests/
      GraphQLRequestTest.php                # Request body structure, variables, endpoint
      MultipartGraphQLRequestTest.php       # Multipart body parts, file attachment
    Resources/
      AccountantResourceTest.php            # Mocked responses → DTO mapping
      AdministrationResourceTest.php
      AppInfoResourceTest.php
      ArchiveCategoryResourceTest.php
      AssociateResourceTest.php
      CustomerResourceTest.php
      DocumentResourceTest.php
      JournalResourceTest.php
      StatisticResourceTest.php
```

## Official Clearfacts API Documentation

- [Developer Portal](https://developer.clearfacts.be/)
- [GraphQL Schema Reference](https://assets-prod.cdn.clearfacts.be/doc/query.doc.html)
- [GitHub Documentation](https://github.com/Clearfacts/cf_developer_docs)
- [Postman Collection](https://www.postman.com/clearfacts/clearfacts-public-api)

**Note:** The Clearfacts API does not support GraphQL introspection. The query/mutation definitions in this SDK are based on the official documentation and schema. If Clearfacts adds or changes endpoints, the request classes in `src/Api/Requests/` can be updated accordingly.

## License

MIT
