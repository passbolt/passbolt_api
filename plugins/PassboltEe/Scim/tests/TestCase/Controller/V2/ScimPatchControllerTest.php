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
        $scimEntry = $this->createScimUser1();

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
        $scimEntry = $this->createScimUser1();

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
     * Test case for PATCH /Users/<user_id> - cannot disable admin user
     */
    public function testScimControllerUsersPatch_CannotDisableAdmin()
    {
        $this->setTestNow();
        $scimEntry = $this->createScimAdminUser();

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => 'False',
            ],
        ]));
        $this->assertResponseCode(403);

        $scimEntry = $this->getScimEntryByName('admin-scim@username.com', addUser: true);
        $this->assertNull($scimEntry->user->disabled);
    }

    /**
     * Test case for PATCH /Users/<user_id> - can disable admin when config allows
     */
    public function testScimControllerUsersPatch_CanDisableAdminWhenConfigAllows()
    {
        $this->setTestNow();
        Configure::write('passbolt.plugins.scim.security.allowSuspendAdministrators', true);
        $scimEntry = $this->createScimAdminUser();

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => 'False',
            ],
        ]));
        $this->assertResponseCode(200);

        $scimEntry = $this->getScimEntryByName('admin-scim@username.com', addUser: true);
        $this->assertNotNull($scimEntry->user->disabled);
    }

    /**
     * Test case for PATCH /Users/<user_id> - can disable regular user
     */
    public function testScimControllerUsersPatch_CanDisableRegularUser()
    {
        $this->setTestNow();
        $scimEntry = $this->createScimUser1();

        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => 'False',
            ],
        ]));
        $this->assertResponseCode(200);

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true);
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

    /**
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ScimResources::build()`.
     */
    public function testScimControllerUsersPatch_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->patch(
            $this->getScimEndpoint('InvalidResourceType' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'),
            $this->getPatchOpData([
                [
                    'op' => 'Replace',
                    'path' => 'name.givenName',
                    'value' => 'irrelevant',
                ],
            ])
        );

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('Invalid Resource type `InvalidResourceType`');
    }
}
