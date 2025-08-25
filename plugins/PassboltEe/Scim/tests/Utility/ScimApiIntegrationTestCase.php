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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\Utility;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Routing\Router;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

/**
 * ScimApiIntegrationTestCase class
 */
abstract class ScimApiIntegrationTestCase extends AppIntegrationTestCase
{
    use ScimTestRequestBodyDataTrait;
    use ScimTestUsersTrait;

    /**
     * Placeholder for setting id value to replace in expected SCIM responses
     */
    public const PLACEHOLDER_SETTING_ID = 'PLACEHOLDER_SETTING_ID';

    /**
     * Placeholder for setting id value to replace in expected SCIM responses
     */
    public const PLACEHOLDER_API_URL = 'PLACEHOLDER_API_URL';

    /**
     * Path to fixture files for SCIM responses
     */
    public const FIXTURE_SCIM_PATH = PLUGINS . 'PassboltEe' . DS . 'Scim' . DS . 'tests' . DS . 'Fixture' . DS . 'Scim' . DS;

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
     * Expected response for GET `/Users/<user_id>` endpoint with existing user
     */
    public const FIXTURE_RESPONSE_USERS_VIEW_SUCCESS = 'Users' . DS . 'users_view_success.json';

    /**
     * Expected response for GET `/Users/<user_id>` endpoint with NOT existing user
     */
    public const FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND = 'Users' . DS . 'users_view_not_found.json';

    /**
     * Expected response for GET `/Users/<user_id>` endpoint with NOT a UUID as identifier
     */
    public const FIXTURE_RESPONSE_USERS_VIEW_NOT_UUID = 'Users' . DS . 'users_view_not_uuid.json';

    /**
     * Setting ID for the SCIM endpoint
     *
     * @var string
     */
    protected string $settingId = '';

    /**
     * Scim user id for the SCIM operations logs
     *
     * @var string|null
     */
    protected ?string $scimUserId = '';

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('Scim');
        $this->enableFeaturePlugin('JwtAuthentication');

        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();
        ScimSettingFactory::make()->default()->persist();
        $this->settingId = ScimSettingFactory::SCIM_TEST_SETTING_ID;
        $this->scimUserId = UserFactory::firstOrFail()->id;
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->resetTestNow();
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
        $text = str_replace(
            self::PLACEHOLDER_API_URL,
            str_replace('"', '', json_encode(Router::url('/', full: true))),
            $text
        );

        return str_replace(self::PLACEHOLDER_SETTING_ID, $this->settingId, $text);
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

    protected function configScimAuth(): void
    {
        $this->configRequest([
            'headers' => [
                'Authorization' => 'Bearer ' . ScimSettingFactory::SCIM_TEST_SECRET_TOKEN,
            ],
        ]);
    }

    /**
     * Sets HTTP headers for the *next* request to be identified as SCIM+JSON request.
     *
     * @return void
     */
    protected function requestAsScimPlusJson(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/scim+json',
                'Content-Type' => 'application/scim+json',
            ],
        ]);
    }
}
