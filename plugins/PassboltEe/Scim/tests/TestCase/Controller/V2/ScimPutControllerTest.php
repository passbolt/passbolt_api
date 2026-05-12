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

use Cake\Core\Configure;
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
     * Test case for PUT /Users/<user_id> - cannot disable admin user
     */
    public function testScimControllerUsersPut_CannotDisableAdmin()
    {
        $this->setTestNow();
        $scimEntry = $this->createScimAdminUser();

        $putData = $this->getUserPostData('admin-scim@username.com', '7a23c9d1-5f42-4b8e-a1d3-c9e52f8b3a17', 'admin-scim@email.com', 'Admin');
        $putData['active'] = false;
        $putData['name']['familyName'] = 'Scim';

        $this->configScimAuth();
        $this->put($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $putData);
        $this->assertResponseCode(403);

        $scimEntry = $this->getScimEntryByName('admin-scim@username.com', addUser: true);
        $this->assertNull($scimEntry->user->disabled);
    }

    /**
     * Test case for PUT /Users/<user_id> - can disable admin when config allows
     */
    public function testScimControllerUsersPut_CanDisableAdminWhenConfigAllows()
    {
        $this->setTestNow();
        Configure::write('passbolt.plugins.scim.security.allowSuspendAdministrators', true);
        $scimEntry = $this->createScimAdminUser();

        $putData = $this->getUserPostData('admin-scim@username.com', '7a23c9d1-5f42-4b8e-a1d3-c9e52f8b3a17', 'admin-scim@email.com', 'Admin');
        $putData['active'] = false;
        $putData['name']['familyName'] = 'Scim';

        $this->configScimAuth();
        $this->put($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $putData);
        $this->assertResponseCode(200);

        $scimEntry = $this->getScimEntryByName('admin-scim@username.com', addUser: true);
        $this->assertNotNull($scimEntry->user->disabled);
    }

    /**
     * Test case for PATCH /Users/<user_id> endpoint with NOT existing user
     */
    public function testScimControllerUsersPut_NotFound()
    {
        $this->setTestNow();
        $this->configScimAuth();
        $this->put($this->getScimEndpoint('Users' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'), $this->getUserPostData());
        $this->assertResponseCode(404);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_VIEW_NOT_FOUND);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ScimResources::build()`.
     */
    public function testScimControllerUsersPut_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->put(
            $this->getScimEndpoint('InvalidResourceType' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'),
            $this->getUserPostData()
        );

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('Invalid Resource type `InvalidResourceType`');
    }
}
