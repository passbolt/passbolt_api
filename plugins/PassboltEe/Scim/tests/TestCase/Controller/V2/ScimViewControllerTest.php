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

namespace Controller\V2;

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
        $this->get($this->getScimEndpoint('Users' . DS . 'not-existing-id'));
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }
}
