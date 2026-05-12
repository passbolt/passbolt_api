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

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use App\Test\Factory\UserFactory;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * ScimAddControllerTest class
 */
class ScimAddControllerTest extends ScimApiIntegrationTestCase
{
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
        UserFactory::firstOrFail(['username' => 'user1@email.com']);
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
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ScimResources::build()`.
     */
    public function testScimControllerUsersAdd_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->post($this->getScimEndpoint('InvalidResourceType'), $this->getUserPostData());

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('Invalid Resource type `InvalidResourceType`');
    }
}
