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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryUserSettings;

use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryUserSettingsSetControllerTest extends AccountRecoveryIntegrationTestCase
{
    use GpgAdaSetupTrait;

    /**
     * Authentication error guest not allowed
     */
    public function testAccountRecoveryUserSettingsSetController_ErrorAuthentication()
    {
        $this->postJson('/account-recovery/user-settings.json');
        $this->assertError(401);
    }

    public function testAccountRecoveryUserSettingsSetController_Success()
    {
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $orkArmored = $policy->account_recovery_organization_public_key->armored_key;
        $orkFingerprint = $policy->account_recovery_organization_public_key->fingerprint;

        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password_sig_ada.msg'),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->logInAsUser();
        $this->postJson('/account-recovery/user-settings.json', $data);
        $this->assertResponseOk();

        $this->assertSame(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
    }
}
