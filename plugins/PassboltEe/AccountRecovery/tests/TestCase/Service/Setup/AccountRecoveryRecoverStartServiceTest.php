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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoveryRecoverStartService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryRecoverStartServiceTest extends AccountRecoveryTestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = (new AccountRecoveryRecoverStartService());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->service);
    }

    /**
     * Ensure that the account_recovery_organization_policy field is well added to the info if
     * the policy exists
     */
    public function testAccountRecoveryRecoverStartService_GetInfo_WithAccountRecoveryPolicy()
    {
        $status = 'Foo';
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser()
            ->setField('status', $status)
            ->persist();
        $user = $setting->user;
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $info = $this->service->getInfo($user->id, $token->token);

        $this->assertNotNull($info['user']);
        $this->assertSame(compact('status'), $info['user']['account_recovery_user_setting']);

        $this->assertEquals([
            'id' => $policy->id,
            'public_key_id' => $policy->public_key_id,
            'policy' => $policy->policy,
            'created' => $policy->created,
            'modified' => $policy->modified,
            'created_by' => $policy->created_by,
            'modified_by' => $policy->modified_by,
            'account_recovery_organization_public_key' => [
                'id' => $policy->account_recovery_organization_public_key->id,
                'armored_key' => $policy->account_recovery_organization_public_key->armored_key,
            ],
        ], $info['account_recovery_organization_policy']);
    }

    /**
     * Ensure that the default account_recovery_organization_policy field is well added to the info if
     * the policy does not exist
     */
    public function testAccountRecoveryRecoverStartService_GetInfo_WithoutAccountRecoveryPolicy()
    {
        $status = 'Foo';
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser()
            ->setField('status', $status)
            ->persist();
        $user = $setting->user;
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();

        $info = $this->service->getInfo($user->id, $token->token);

        $this->assertNotNull($info['user']);
        $this->assertSame(compact('status'), $info['user']['account_recovery_user_setting']);

        $this->assertEquals([
            'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
            'public_key_id' => null,
        ], $info['account_recovery_organization_policy']);
    }
}
