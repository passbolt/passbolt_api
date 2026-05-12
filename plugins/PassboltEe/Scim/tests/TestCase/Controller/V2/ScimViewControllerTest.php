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

use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * ScimViewControllerTest class
 */
class ScimViewControllerTest extends ScimApiIntegrationTestCase
{
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
        $this->get($this->getScimEndpoint('Users' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'));
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Test case for GET /Users/<user_id> endpoint with user ID not UUID
     */
    public function testScimControllerUsersView_NotUUID()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . 'not-a-uuid'));
        $this->assertResponseCode(400);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_UUID);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ScimResources::build()`.
     */
    public function testScimControllerUsersView_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('InvalidResourceType' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'));

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('Invalid Resource type `InvalidResourceType`');
    }
}
