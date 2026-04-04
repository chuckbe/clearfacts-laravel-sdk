<?php

namespace Clearfacts\Tests\Unit\Resources;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\Data\Associate;
use Clearfacts\Data\AssociateGroup;
use Clearfacts\Enums\AssociateType;
use Clearfacts\Enums\Language;
use Clearfacts\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class AssociateResourceTest extends TestCase
{
    public function test_list_returns_array_of_associates(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'associates' => [
                        [
                            'id' => '1',
                            'firstName' => 'Jan',
                            'lastName' => 'Peeters',
                            'email' => 'jan@example.be',
                            'language' => 'nl_BE',
                            'type' => 'ADMIN',
                            'active' => true,
                            'associateGroups' => [
                                ['id' => 'g1', 'name' => 'Group A'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->associate->list();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Associate::class, $result[0]);
        $this->assertSame('Jan', $result[0]->firstName);
        $this->assertSame('Peeters', $result[0]->lastName);
        $this->assertSame('ADMIN', $result[0]->type);
        $this->assertTrue($result[0]->active);
        $this->assertCount(1, $result[0]->associateGroups);
        $this->assertInstanceOf(AssociateGroup::class, $result[0]->associateGroups[0]);
        $this->assertSame('Group A', $result[0]->associateGroups[0]->name);
    }

    public function test_groups_returns_array_of_associate_groups(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'associateGroups' => [
                        ['id' => 'g1', 'name' => 'Audit'],
                        ['id' => 'g2', 'name' => 'Tax'],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->associate->groups();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(AssociateGroup::class, $result[0]);
        $this->assertSame('Audit', $result[0]->name);
        $this->assertSame('Tax', $result[1]->name);
    }

    public function test_add_returns_associate_with_plain_password(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'addAssociate' => [
                        'id' => 'new-1',
                        'firstName' => 'Marie',
                        'lastName' => 'Dupont',
                        'email' => 'marie@example.be',
                        'language' => 'fr_BE',
                        'type' => 'ASSOCIATE',
                        'active' => true,
                        'associateGroups' => [],
                        'plainPassword' => 'generated-pass-123',
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->associate->add(
            firstName: 'Marie',
            lastName: 'Dupont',
            email: 'marie@example.be',
            type: AssociateType::ASSOCIATE,
            active: true,
            language: Language::FRENCH,
            sendActivationMail: false,
        );

        $this->assertInstanceOf(Associate::class, $result);
        $this->assertSame('new-1', $result->id);
        $this->assertSame('Marie', $result->firstName);
        $this->assertSame('generated-pass-123', $result->plainPassword);
    }

    public function test_edit_returns_updated_associate(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'data' => [
                    'editAssociate' => [
                        'id' => '1',
                        'firstName' => 'Jan-Updated',
                        'lastName' => 'Peeters',
                        'email' => 'jan@example.be',
                        'language' => 'nl_BE',
                        'type' => 'ADMIN',
                        'active' => false,
                        'associateGroups' => [],
                    ],
                ],
            ]),
        ]);

        $client = new ClearfactsApiClient('test-token');
        $client->withMockClient($mockClient);

        $result = $client->associate->edit('1', [
            'firstName' => 'Jan-Updated',
            'active' => false,
        ]);

        $this->assertInstanceOf(Associate::class, $result);
        $this->assertSame('Jan-Updated', $result->firstName);
        $this->assertFalse($result->active);
    }
}
