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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryOrganizationPolicies;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryOrganizationPoliciesSetControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

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

    public function testAccountRecoveryOrganizationPoliciesSetController_Success()
    {
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];

        $keyData = AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()->getEntity();
        $policyValue = 'opt-in';
        $data = [
            'policy' => $policyValue,
            'account_recovery_organization_public_key' => [
                'fingerprint' => $keyData->fingerprint,
                'armored_key' => $keyData->armored_key,
            ],
        ];
        $this->logInAs($user);
        $this->postJson('/account-recovery/organization-policies.json', $data);
        $this->assertResponseOk();

        // Check data integrity
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertEquals(1, AccountRecoveryOrganizationPublicKeyFactory::count());

        // Check emails
        $this->assertEmailQueueCount(count($admins));
        foreach ($admins as $admin) {
            if ($admin->id === $user->id) {
                $this->assertEmailInBatchContains("You have set the account recovery organization policy to $policyValue.", $admin->username);
            } else {
                $this->assertEmailInBatchContains($user->profile->first_name . " has set the account recovery organization policy to $policyValue.", $admin->username);
            }
            $this->assertEmailInBatchContains("The fingerprint of the organization public key is $keyData->fingerprint.", $admin->username);
        }
    }
}
