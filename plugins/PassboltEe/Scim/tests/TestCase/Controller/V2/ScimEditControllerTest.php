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
 * ScimEditControllerTest class
 */
class ScimEditControllerTest extends ScimApiIntegrationTestCase
{
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
        $this->patch($this->getScimEndpoint('Users' . DS . 'not-existing-id'), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'name.givenName',
                'value' => 'First name replaced',
            ],
        ]));
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }
}
