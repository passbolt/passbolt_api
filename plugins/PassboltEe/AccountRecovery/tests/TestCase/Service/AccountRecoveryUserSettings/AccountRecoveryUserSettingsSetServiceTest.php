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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryUserSettings;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Http\Exception\BadRequestException;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsSetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryUserSettingsSetServiceTest extends AccountRecoveryTestCase
{
    use GpgAdaSetupTrait;

    /**
     * @var AccountRecoveryUserSettingsSetService $service
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new AccountRecoveryUserSettingsSetService(
            UserFactory::make()->active()->user()->persistedUAC()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    /**
     * @Given the organization policy is mandatory
     * @When a setting is set to rejected
     * @Then an exception should be thrown
     */
    public function testAccountRecoveryUserSettingsSetService_Errors_Mandatory_Rejected()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();
        $status = AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED;

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request. You cannot opt-out.');
        try {
            $this->service->set(compact('status'));
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_Mandatory_Approved()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();
        $status = AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED;

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request. Private key or password are missing.');
        try {
            $this->service->set(compact('status'));
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_Mandatory_No_Status()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The account recovery user setting is not valid.');
        try {
            $this->service->set(['foo']);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_Mandatory_Wrong_Status()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The status should be one of the following: rejected, approved.');
        try {
            $this->service->set(['status' => 'foo']);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_OrgSetting_Deactivated()
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Account recovery is disabled.');
        try {
            // Status here does not matter
            $this->service->set([]);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_MandatoryAcceptedPassswordMissing()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->getDummyPrivateKey(),
            ],
        ];

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request. Private key or password are missing.');
        try {
            $this->service->set($data);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_InvalidKey_NotOpenPGP()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => 'Foo',
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate private key data.');
        try {
            $this->service->set($data);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_InvalidKey_NotSymmetricBlock()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate private key data.');
        try {
            $this->service->set($data);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Errors_InvalidPasswords_WrongFingerprint()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->getDummyPrivateKey(),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAB',
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate password data.');
        try {
            $this->service->set($data);
        } catch (\Exception $e) {
            $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
            throw $e;
        }
    }

    public function testAccountRecoveryUserSettingsSetService_Success_Approved()
    {
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        $orkArmored = $policy->account_recovery_organization_public_key->armored_key;
        $orkFingerprint = $policy->account_recovery_organization_public_key->fingerprint;

        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->getDummyPrivateKey(),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->service->set($data);

        $this->assertSame(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
    }

    public function testAccountRecoveryUserSettingsSetService_Success_Rejected()
    {
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED,
        ];

        $this->service->set($data);

        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
    }

    public function testAccountRecoveryUserSettingsSetService_Error_EnrollTwice()
    {
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        $orkArmored = $policy->account_recovery_organization_public_key->armored_key;
        $orkFingerprint = $policy->account_recovery_organization_public_key->fingerprint;

        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->getDummyPrivateKey(),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->service->set($data);

        $this->expectException(BadRequestException::class);
        $this->service->set($data);
    }

    public function testAccountRecoveryUserSettingsSetService_Error_Deenroll()
    {
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        $orkArmored = $policy->account_recovery_organization_public_key->armored_key;
        $orkFingerprint = $policy->account_recovery_organization_public_key->fingerprint;

        $data = [
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
            'account_recovery_private_key' => [
                'data' => $this->getDummyPrivateKey(),
                'account_recovery_private_key_passwords' => [[
                    'recipient_fingerprint' => $orkFingerprint,
                    'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                    'data' => $this->encrypt($orkFingerprint, $orkArmored),
                ]],
            ],
        ];

        $this->service->set($data);

        $this->expectException(BadRequestException::class);
        $this->service->set([
            'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED,
        ]);
    }
}
