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

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

/**
 * ScimControllerTest class
 */
class ScimControllerTest extends BaseIntegrationTest
{
    /**
     * Expected response for `/ServiceProviderConfig` endpoint
     */
    public CONST FIXTURE_RESPONSE_SERVICE_PROVIDER_CONFIG = 'service_provider_config.json';

    /**
     * Expected response for `/Schemas` endpoint
     */
    public CONST FIXTURE_RESPONSE_SCHEMAS = 'schemas.json';

    /**
     * Expected response for `/Schemas/urn:ietf:params:scim:schemas:core:2.0:User` endpoint
     */
    public CONST FIXTURE_RESPONSE_SCHEMAS_USERS = 'schemas_users.json';

    /**
     * Expected response for `/Schemas/urn:ietf:params:scim:schemas:core:2.0:Group` endpoint
     */
    public CONST FIXTURE_RESPONSE_SCHEMAS_GROUPS = 'schemas_groups.json';

    /**
     * Expected response for `/Schemas/<schemaId>` endpoint with invalid id
     */
    public CONST FIXTURE_RESPONSE_SCHEMAS_NOT_FOUND = 'schemas_not_found.json';

    /**
     * Expected response for `/ResourceTypes` endpoint
     */
    public CONST FIXTURE_RESPONSE_RESOURCE_TYPES = 'resource_types.json';

    /**
     * Expected response for `/ResourceTypes/User` endpoint
     */
    public CONST FIXTURE_RESPONSE_RESOURCE_TYPES_USER = 'resource_types_user.json';

    /**
     * Expected response for `/ResourceTypes/Group` endpoint
     */
    public CONST FIXTURE_RESPONSE_RESOURCE_TYPES_GROUP = 'resource_types_group.json';

    /**
     * Expected response for `/ResourceTypes/InvalidResource` endpoint
     */
    public CONST FIXTURE_RESPONSE_RESOURCE_TYPES_NOT_FOUND = 'resource_types_not_found.json';

    /**
     * Expected response for `/Users` endpoint with no filter
     */
    public CONST FIXTURE_RESPONSE_USERS_LIST = 'Users' . DS . 'users_list_response.json';

    /**
     * Expected response for `/Users` endpoint with matching filter
     */
    public CONST FIXTURE_RESPONSE_USERS_LIST_MATCH = 'Users' . DS . 'users_list_response_filter_match.json';

    /**
     * Expected response for `/Users` endpoint withNO  matching filter
     */
    public CONST FIXTURE_RESPONSE_USERS_LIST_NO_MATCH = 'Users' . DS . 'users_list_response_filter_no_match.json';

    /**
     * Expected response for POST `/Users` endpoint
     */
    public CONST FIXTURE_RESPONSE_USERS_ADD_SUCCESS = 'Users' . DS . 'users_add_success.json';

    /**
     * Expected response for POST `/Users` endpoint for an existing user
     */
    public CONST FIXTURE_RESPONSE_USERS_ADD_CONFLICT_EXIST = 'Users' . DS . 'users_add_conflict_exist.json';

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
     * @dataProvider providerRestScimControllerUsersList
     */
    public function testScimControllerUsersList(string $endpoint, string $expectedResponseFile)
    {
        $this->setTestNow();
        $scimEntry1 = $this->createScimUser1();
        $scimEntry2 = $this->createScimUser2();

        $expectedResponse = $this->getScimFixtureData($expectedResponseFile);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry1, 1);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry2, 2);

        $this->get($this->getScimEndpoint($endpoint));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Provider for testScimControllerUsersList
     *
     * @return array[]
     */
    public static function providerRestScimControllerUsersList(): array
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
    public function testScimControllerUsersPost_Success()
    {
        $this->setTestNow();

        /** @var \App\Model\Entity\Role $role */
        RoleFactory::make()->user()->persist();
        // @todo: make this user the one selected in the scim settings for logs
        UserFactory::make()->admin()->persist();

        $scimName = 'user1@username.com';
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
    public function testScimControllerUsersPost_ConflictExist()
    {
        $scimName = 'user1@username.com';
        $email = 'user1@email.com';
        $scimEntry = ScimEntryFactory::make(['scim_name' => $scimName])->withUser(['username' => $email])->persist();

        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $email));
        $this->assertResponseCode(409);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_ADD_CONFLICT_EXIST);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry, 1);
        $this->assertResponseEquals($expectedResponse);
    }
}
