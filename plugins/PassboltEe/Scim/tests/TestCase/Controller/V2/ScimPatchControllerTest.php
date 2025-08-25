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
 * ScimPatchControllerTest class
 */
class ScimPatchControllerTest extends ScimApiIntegrationTestCase
{
    /**
     * Test case for success PATCH /Users/<user_id> endpoint
     */
    public function testScimControllerUsersPatch_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $this->createScimUser1();
        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
        $this->assertSame('User 1', $scimEntry->user->profile->first_name);
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);
        $this->assertNull($scimEntry->user->disabled);

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'name.givenName',
                'value' => 'First name replaced',
            ],
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => 'False',
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
        $this->assertNotNull($scimEntry->user->disabled);
    }

    /**
     * Test case for success PATCH /Users/<user_id> endpoint
     */
    public function testScimControllerUsersPatch_AlternativeStructure_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $this->createScimUser1();
        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
        $this->assertSame('User 1', $scimEntry->user->profile->first_name);
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);
        $this->assertNull($scimEntry->user->disabled);

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'value' => [
                    'name.givenName' => 'First name replaced',
                    'active' => 'False',
                ],
            ],
            [
                'op' => 'Add',
                'value' => [
                    'name.familyName' => 'Last name added',
                    'active' => 'False',
                ],
            ],
        ]));
        $this->assertResponseCode(200);

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
        $this->assertSame('First name replaced', $scimEntry->user->profile->first_name);
        // Last name did not change because `Add` operation do not change not empty values
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);
        $this->assertNotNull($scimEntry->user->disabled);
    }

    /**
     * Test case for PATCH /Users/<user_id> endpoint with NOT existing user
     */
    public function testScimControllerUsersPatch_NotFound()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'), $this->getPatchOpData([
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
