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
 * ScimPutControllerTest class
 */
class ScimPutControllerTest extends ScimApiIntegrationTestCase
{
    /**
     * Test case for success PUT /Users/<user_id> endpoint
     */
    public function testScimControllerUsersPut_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame('user1@username.com', $scimEntry->scim_name);
        $this->assertSame('User 1', $scimEntry->user->profile->first_name);
        $this->assertSame('Scim', $scimEntry->user->profile->last_name);
        $this->assertNull($scimEntry->user->disabled);

        $putData = $this->getUserPostData($scimEntry->scim_name);
        $putData['active'] = false;
        $putData['name']['givenName'] = 'updated first name';
        $putData['name']['familyName'] = 'updated last name';

        $this->configScimAuth();
        $this->put($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $putData);
        $this->assertResponseCode(200);
        $this->assertResponseContains('"urn:ietf:params:scim:schemas:core:2.0:User"');
        $this->assertResponseContains('"userName": "user1@username.com"');
        $this->assertResponseContains('"givenName": "updated first name"');
        $this->assertResponseContains('"familyName": "updated last name"');
        $this->assertResponseContains('"active": false');

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
        $this->assertSame('updated first name', $scimEntry->user->profile->first_name);
        $this->assertSame('updated last name', $scimEntry->user->profile->last_name);
        $this->assertSame(self::DATETIME_TEST_NOW, $scimEntry->user->disabled->format('Y-m-d H:i:s'));
    }

    /**
     * Test case for PATCH /Users/<user_id> endpoint with NOT existing user
     */
    public function testScimControllerUsersPut_NotFound()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->put($this->getScimEndpoint('Users' . DS . 'not-existing-id'), $this->getUserPostData());
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }
}
