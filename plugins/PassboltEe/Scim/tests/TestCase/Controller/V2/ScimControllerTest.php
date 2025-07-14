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
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * ScimControllerTest class
 */
class ScimControllerTest extends AppIntegrationTestCase
{
    /**
     * Placeholder for setting id value to replace in expected SCIM responses
     */
    public const SETTING_ID_PLACEHOLDER = 'SETTING_ID_PLACEHOLDER';

    /**
     * Path to fixture files for SCIM responses
     */
    public const FIXTURE_SCIM_PATH = PLUGINS . 'PassboltEe' . DS . 'Scim' . DS . 'tests' . DS . 'Fixture' . DS . 'Scim' . DS;

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
     * Setting ID for the scim endpoint
     *
     * @var string
     */
    protected string $settingId = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('Scim');
        // @todo: Generate a valid settingId when SCIM auth is fixed
        $this->settingId = '123456789';
    }

    /**
     * @param string $action
     * @return string
     */
    protected function getScimEndpoint(string $action): string
    {
        return '/scim/v2/' . $this->settingId . '/' . $action;
    }

    /**
     * @param string $text
     * @return string
     */
    protected function replaceSettingIdString(string $text): string
    {
        return str_replace(self::SETTING_ID_PLACEHOLDER, $this->settingId, $text);
    }

    /**
     * Return the content of a scim fixture response, replacing the setting id placeholder if needed
     * Note: trim is done to remove possible end of file break line in the fixture file
     *
     * @param string $filename
     * @return string
     */
    protected function getScimFixtureData(string $filename): string
    {
        return trim($this->replaceSettingIdString(file_get_contents(self::FIXTURE_SCIM_PATH . $filename)));
    }

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
     * Test case for POST /Users endpoint
     *
     * Scenario: The user does not exist in passbolt and there is no SCIM entry table for that user.
     */
    public function testScimControllerUsersPost_Success()
    {
        /** @var \App\Model\Entity\Role $role */
        $role = RoleFactory::make()->user()->persist();
        UserFactory::make()->admin()->persist();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \Passbolt\Scim\Model\Table\ScimEntriesTable $scimEntriesTable */
        $scimEntriesTable = $this->fetchTable('Scim.ScimEntries');

        $scimName = 'user1@username.com';
        $externalId = 'scim_user_1';
        $email = 'user1@test.com';
        $this->post($this->getScimEndpoint('Users'), [
            'schemas' => [
                'urn:ietf:params:scim:schemas:core:2.0:User',
                'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User',
            ],
            'externalId' => $externalId,
            'userName' => $scimName,
            'active' => true,
            'displayName' => 'User 1 Scim',
            'emails' => [
                [
                    'primary' => true,
                    'type' => 'work',
                    'value' => $email,
                ]
            ],
            'meta' => [
                'resourceType' => 'User',
            ],
            'name' => [
                'formatted' => 'User 1 Scim',
                'familyName' => 'Scim',
                'givenName' => 'User 1',
            ],
        ]);
        $this->assertResponseCode(201);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"externalId": "' . $externalId . '"');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"value": "' . $email . '"');
        $this->assertResponseContains('"familyName": "Scim"');
        $this->assertResponseContains('"givenName": "User 1"');

        /** @var \App\Model\Entity\User|null $existingUser */
        $existingUser = $usersTable
            ->find()
            ->where(['Users.username' => $email])
            ->first();
        $this->assertNotNull($existingUser);
        $this->assertSame($email, $existingUser->username);
        $this->assertSame($role->id, $existingUser->role_id);

        /** @var \Passbolt\Scim\Model\Entity\ScimEntry|null $existingScimEntry */
        $existingScimEntry = $scimEntriesTable
            ->find()
            ->where(['ScimEntries.scim_name' => $scimName])
            ->first();
        $this->assertNotNull($existingScimEntry);
        $this->assertSame('users', $existingScimEntry->foreign_model);
        $this->assertSame($existingUser->id, $existingScimEntry->foreign_key);
        $this->assertSame($scimName, $existingScimEntry->scim_name);
        $this->assertSame($externalId, $existingScimEntry->external_identifier);

    }
}
