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
namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class SetupStartControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * @group Requests
     * @group setup
     * @group setupStart
     */
    public function testAccountRecoverySetupStartSuccess()
    {
        $user = UserFactory::make()->inactive()->persist();
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->persist();

        $url = "/setup/start/{$user->id}/{$token->token}.json";
        $this->getJson($url);
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('user', $this->_responseJsonBody);
        $this->assertObjectHasAttribute('account_recovery_organization_policy', $this->_responseJsonBody);
        $this->assertSame($policy->id, $this->_responseJsonBody->account_recovery_organization_policy->id);
        $this->assertSame($policy->policy, $this->_responseJsonBody->account_recovery_organization_policy->policy);
        $this->assertSame($policy->public_key_id, $this->_responseJsonBody->account_recovery_organization_policy->public_key_id);
        $this->assertSame($policy->account_recovery_organization_public_key->armored_key, $this->_responseJsonBody->account_recovery_organization_policy->account_recovery_organization_public_key->armored_key);
        $this->assertSame($policy->account_recovery_organization_public_key->fingerprint, $this->_responseJsonBody->account_recovery_organization_policy->account_recovery_organization_public_key->fingerprint);
        $this->assertSame($policy->account_recovery_organization_public_key->id, $this->_responseJsonBody->account_recovery_organization_policy->account_recovery_organization_public_key->id);
    }
}
