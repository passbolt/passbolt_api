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
 * @since         3.5.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryOrganizationPolicies;

use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryOrganizationPoliciesSetControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * check setting organization policies without being logged in triggers an error
     */
    public function testAccountRecoveryOrganizationPoliciesSetController_ErrorNotLoggedIn()
    {
        $this->postJson('/account-recovery/organization-policies.json', []);
        $this->assertResponseCode(401);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * check setting organization policies without being logged in as an admin
     */
    public function testAccountRecoveryOrganizationPoliciesSetController_ErrorNotLoggedInAsAdmin()
    {
        $this->logInAsUser();
        $this->postJson('/account-recovery/organization-policies.json', []);
        $this->assertResponseCode(403);
        $this->assertResponseError('You are not allowed to access this location.');
    }
}
