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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Test\Utility;

/**
 * Trait with utility function for testing scim users operations
 *
 * @mixin \Passbolt\Scim\Test\Utility\BaseIntegrationTest
 */
trait ScimIntegrationTestTrait
{
    /**
     * @param string $userId
     * @param string $scimName
     * @return void
     */
    protected function assertScimUserExistRequestById_Success(string $userId, string $scimName): void
    {
        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $userId));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"id": "' . $userId . '"');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
    }
}
