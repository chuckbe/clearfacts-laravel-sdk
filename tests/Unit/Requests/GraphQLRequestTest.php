<?php

namespace Clearfacts\Tests\Unit\Requests;

use Clearfacts\Api\Requests\Administration\GetAdministrationRequest;
use Clearfacts\Api\Requests\Administration\GetAdministrationsRequest;
use Clearfacts\Api\Requests\Customer\GetCustomersRequest;
use Clearfacts\Tests\TestCase;
use Saloon\Enums\Method;

class GraphQLRequestTest extends TestCase
{
    public function test_request_uses_post_method(): void
    {
        $request = new GetAdministrationsRequest();

        $this->assertSame(Method::POST, $request->getMethod());
    }

    public function test_request_resolves_graphql_endpoint(): void
    {
        $request = new GetAdministrationsRequest();

        $this->assertSame('/graphql', $request->resolveEndpoint());
    }

    public function test_request_without_variables_omits_variables_key(): void
    {
        $request = new GetAdministrationsRequest();
        $body = $request->body()->all();

        $this->assertArrayHasKey('query', $body);
        $this->assertArrayNotHasKey('variables', $body);
        $this->assertStringContainsString('administrations', $body['query']);
    }

    public function test_request_with_variables_includes_them(): void
    {
        $request = new GetAdministrationRequest('0123456789');
        $body = $request->body()->all();

        $this->assertArrayHasKey('query', $body);
        $this->assertArrayHasKey('variables', $body);
        $this->assertSame('0123456789', $body['variables']['companyNumber']);
    }

    public function test_request_with_nullable_variables_filters_nulls(): void
    {
        $request = new GetCustomersRequest('0123456789', null);
        $body = $request->body()->all();

        $this->assertArrayHasKey('variables', $body);
        $this->assertSame('0123456789', $body['variables']['companyNumber']);
        $this->assertArrayNotHasKey('offset', $body['variables']);
    }

    public function test_request_with_offset_includes_it(): void
    {
        $request = new GetCustomersRequest('0123456789', 100);
        $body = $request->body()->all();

        $this->assertSame(100, $body['variables']['offset']);
    }
}
