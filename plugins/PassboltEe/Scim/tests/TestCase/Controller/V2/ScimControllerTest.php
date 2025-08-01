<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.6.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use Passbolt\Scim\Test\Factory\ScimEntryFactory;
use Passbolt\Scim\Test\Utility\BaseIntegrationTest;

/**
 * ScimControllerTest class
 */
class ScimControllerTest extends BaseIntegrationTest
{
    /**
     * Expected response for `/ServiceProviderConfig` endpoint
     */
    public const FIXTURE_RESPONSE_SERVICE_PROVIDER_CONFIG = 'service_provider_config.json';

    /**
     * Expected response for `/Schemas` endpoint
     */
    public const FIXTURE_RESPONSE_SCHEMAS = 'schemas.json';

    /**
     * Expected response for `/Schemas/urn:ietf:params:scim:schemas:core:2.0:User` endpoint
     */
    public const FIXTURE_RESPONSE_SCHEMAS_USERS = 'schemas_users.json';

    /**
     * Expected response for `/Schemas/urn:ietf:params:scim:schemas:core:2.0:Group` endpoint
     */
    public const FIXTURE_RESPONSE_SCHEMAS_GROUPS = 'schemas_groups.json';

    /**
     * Expected response for `/Schemas/<schemaId>` endpoint with invalid id
     */
    public const FIXTURE_RESPONSE_SCHEMAS_NOT_FOUND = 'schemas_not_found.json';

    /**
     * Expected response for `/ResourceTypes` endpoint
     */
    public const FIXTURE_RESPONSE_RESOURCE_TYPES = 'resource_types.json';

    /**
     * Expected response for `/ResourceTypes/User` endpoint
     */
    public const FIXTURE_RESPONSE_RESOURCE_TYPES_USER = 'resource_types_user.json';

    /**
     * Expected response for `/ResourceTypes/Group` endpoint
     */
    public const FIXTURE_RESPONSE_RESOURCE_TYPES_GROUP = 'resource_types_group.json';

    /**
     * Expected response for `/ResourceTypes/InvalidResource` endpoint
     */
    public const FIXTURE_RESPONSE_RESOURCE_TYPES_NOT_FOUND = 'resource_types_not_found.json';

    /**
     * Expected response for `/Users` endpoint with no filter
     */
    public const FIXTURE_RESPONSE_USERS_LIST = 'Users' . DS . 'users_list_response.json';

    /**
     * Expected response for `/Users` endpoint with matching filter
     */
    public const FIXTURE_RESPONSE_USERS_LIST_MATCH = 'Users' . DS . 'users_list_response_filter_match.json';

    /**
     * Expected response for `/Users` endpoint withNO  matching filter
     */
    public const FIXTURE_RESPONSE_USERS_LIST_NO_MATCH = 'Users' . DS . 'users_list_response_filter_no_match.json';

    /**
     * Expected response for POST `/Users` endpoint
     */
    public const FIXTURE_RESPONSE_USERS_ADD_SUCCESS = 'Users' . DS . 'users_add_success.json';

    /**
     * Expected response for POST `/Users` endpoint for an existing user
     */
    public const FIXTURE_RESPONSE_USERS_ADD_CONFLICT_EXIST = 'Users' . DS . 'users_add_conflict_exist.json';

    /**
     * Expected response for POST `/Users/<user_id>` endpoint with existing user
     */
    public const FIXTURE_RESPONSE_USERS_VIEW_SUCCESS = 'Users' . DS . 'users_view_success.json';

    /**
     * Expected response for POST `/Users/<user_id>` endpoint with NOT existing user
     */
    public const FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND = 'Users' . DS . 'users_view_not_found.json';

    /**
     * Test case
     */
    public function testScimControllerServiceProviderConfig_Success()
    {
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SERVICE_PROVIDER_CONFIG));
    }

    /**
     * Test case
     */
    public function testScimControllerSchemas_Success()
    {
        $this->get($this->getScimEndpoint('Schemas'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SCHEMAS));
    }

    /**
     * Test case
     */
    public function testScimControllerSchemasUsers_Success()
    {
        $this->get($this->getScimEndpoint('Schemas' . DS . 'urn:ietf:params:scim:schemas:core:2.0:User'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SCHEMAS_USERS));
    }

    /**
     * Test case
     */
    public function testScimControllerSchemasGroups_Success()
    {
        $this->get($this->getScimEndpoint('Schemas' . DS . 'urn:ietf:params:scim:schemas:core:2.0:Group'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SCHEMAS_GROUPS));
    }

    /**
     * Test case
     */
    public function testScimControllerSchemasGroups_NotFound()
    {
        $this->get($this->getScimEndpoint('Schemas' . DS . 'InvalidSchema'));
        $this->assertResponseCode(404);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SCHEMAS_NOT_FOUND));
    }

    /**
     * Test case
     */
    public function testScimControllerResourceTypes_Success()
    {
        $this->get($this->getScimEndpoint('ResourceTypes'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_RESOURCE_TYPES));
    }

    /**
     * Test case
     */
    public function testScimControllerResourceTypesUser_Success()
    {
        $this->get($this->getScimEndpoint('ResourceTypes' . DS . 'User'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_RESOURCE_TYPES_USER));
    }

    /**
     * Test case
     */
    public function testScimControllerResourceTypesGroup_Success()
    {
        $this->get($this->getScimEndpoint('ResourceTypes' . DS . 'Group'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_RESOURCE_TYPES_GROUP));
    }

    /**
     * Test case
     */
    public function testScimControllerResourceTypesGroup_NotFound()
    {
        $this->get($this->getScimEndpoint('ResourceTypes' . DS . 'InvalidResource'));
        $this->assertResponseCode(404);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_RESOURCE_TYPES_NOT_FOUND));
    }

    /**
     * Test case
     *
     * @param string $endpoint
     * @param string $expectedResponseFile
     * @return void
     * @dataProvider providerRestScimControllerUsersIndex
     */
    public function testScimControllerUsersIndex(string $endpoint, string $expectedResponseFile)
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry1 */
        $scimEntry1 = $this->createScimUser1();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry2 */
        $scimEntry2 = $this->createScimUser2();

        $expectedResponse = $this->getScimFixtureData($expectedResponseFile);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry1, 1);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry2, 2);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint($endpoint));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Provider for testScimControllerUsersIndex
     *
     * @return array[]
     */
    public static function providerRestScimControllerUsersIndex(): array
    {
        return [
            'no-filter' => [
                'endpoint' => 'Users',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST,
            ],
            'no-match-filter' => [
                'endpoint' => 'Users?filter=userName+eq+%22user-not-exist%40username.com%22',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST_NO_MATCH,
            ],
            'match-filter' => [
                'endpoint' => 'Users?filter=userName+eq+%22user1%40username.com%22',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST_MATCH,
            ],
        ];
    }

    /**
     * Test case for success POST /Users endpoint
     */
    public function testScimControllerUsersAdd_Success()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $this->configScimAuth();
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName));
        $this->assertResponseCode(201);

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_ADD_SUCCESS);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry, 1);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for POST /Users endpoint with exist conflict
     */
    public function testScimControllerUsersAdd_ConflictExist()
    {
        $scimName = self::USER_1_SCIM_NAME;
        $email = self::USER_1_EMAIL;
        $scimEntry = ScimEntryFactory::make(['scim_name' => $scimName])->withUser(['username' => $email])->persist();

        $this->configScimAuth();
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $email));
        $this->assertResponseCode(409);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_ADD_CONFLICT_EXIST);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry, 1);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for GET /Users/<user_id> endpoint with existing user
     */
    public function testScimControllerUsersView_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(200);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_SUCCESS);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry, 1);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for GET /Users/<user_id> endpoint with NOT existing user
     */
    public function testScimControllerUsersView_NotFound()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . 'not-existing-id'));
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for success PATCH /Users/<user_id> endpoint
     */
    public function testScimControllerUsersEdit_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame('User 1', $scimEntry->user->profile->first_name);
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'name.givenName',
                'value' => 'First name replaced',
            ],
            [
                'op' => 'Add',
                'path' => 'name.familyName',
                'value' => 'Last name added',
            ],
        ]));
        $this->assertResponseCode(200);

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
        $this->assertSame('First name replaced', $scimEntry->user->profile->first_name);
        // Last name did not change because `Add` operation do not change not empty values
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);
    }

    /**
     * Test case for PATCH /Users/<user_id> endpoint with NOT existing user
     */
    public function testScimControllerUsersEdit_NotFound()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . 'not-existing-id'));
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for success DELETE /Users/<user_id> endpoint
     */
    public function testScimControllerUsersDelete_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame(self::USER_1_SCIM_NAME, $scimEntry->scim_name);
        $this->assertFalse($scimEntry->user->deleted);

        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(204);

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true, isDeleted: true);
        $this->assertTrue($scimEntry->user->deleted);
    }
}
