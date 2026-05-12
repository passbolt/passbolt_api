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
 * ScimAddControllerTest class
 */
class ScimDeleteControllerTest extends ScimApiIntegrationTestCase
{
    /**
     * Test case for success DELETE /Users/<user_id> endpoint
     */
    public function testScimControllerUsersDelete_Success()
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame(self::USER_1_SCIM_NAME, $scimEntry->scim_name);
        $this->assertFalse($scimEntry->user->deleted);

        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(204);

        $scimEntry = $this->getScimEntryByName(self::USER_1_SCIM_NAME, addUser: true, isDeleted: true);
        $this->assertTrue($scimEntry->user->deleted);
    }

    /**
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ScimResources::build()`.
     */
    public function testScimControllerUsersDelete_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('InvalidResourceType' . DS . 'e5bb8c65-2dab-51c3-b82b-438c77a8c2e8'));

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('Invalid Resource type `InvalidResourceType`');
    }
}
