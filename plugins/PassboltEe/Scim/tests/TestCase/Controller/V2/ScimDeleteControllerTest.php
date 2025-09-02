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
}
