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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Plugin;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoverySetupCompleteService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoverySetupCompleteServiceTest extends AccountRecoveryTestCase
{
    use GpgAdaSetupTrait;

    public function setUp(): void
    {
        parent::setUp();

        (new Plugin())->addAssociationsToUsersTable();
    }

    /**
     * If the user setting rejects the account recovery, no private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Success_OptinRejected()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED);

        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);

        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
    }

    /**
     * If the user setting accepts the account recovery, the private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Success_OptinApproved()
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

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $user = $token->user;

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.user_id', $user->id)
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);

        $this->assertSame(1, GpgkeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());

        /** @var AccountRecoveryUserSetting $q */
        $q = AccountRecoveryUserSettingFactory::find()->contain(['Users'])->first();
        $this->assertTrue($q->user->active);
        $this->assertTrue(isset($q->created));
        $this->assertTrue(isset($q->modified));
        $this->assertEquals('approved', $q->status);
        $this->assertEquals($token->user->id, $q->created_by);
        $this->assertEquals($token->user->id, $q->modified_by);

        $pk = AccountRecoveryPrivateKeyFactory::find()->firstOrFail();
        $this->assertSame($user->id, $pk->get('user_id'));
    }

    /**
     * If the user setting rejects the account recovery, no private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Success_MandatoryApproved()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);

        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());

        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $q = $table->find()->contain(['Users'])->all()->first();
        $this->assertTrue($q->user->active);
        $this->assertTrue(isset($q->created));
        $this->assertTrue(isset($q->modified));
        $this->assertEquals('approved', $q->status);
        $this->assertEquals($token->user->id, $q->created_by);
        $this->assertEquals($token->user->id, $q->modified_by);
    }

    public function testAccountRecoverySetupCompleteService_Errors_OrgPolicyDisabled()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'nope',
            ]]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Account recovery is disabled.');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_OptinRejectedWithData()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->optin()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'nope',
            ]]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_MandatoryStatusRejected()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'nope',
            ]]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_MandatoryKeyMissing()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey());

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Account recovery is mandatory');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_MandatoryAcceptedPassswordMissing()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey());

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Account recovery is mandatory.');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_OptinAcceptedPassswordMissing()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->optin()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey());

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    /**
     * The user setting status should be valid
     *
     * @see AccountRecoveryUserSettingsTable::validationDefault()
     */
    public function testAccountRecoverySetupCompleteService_Errors_InvalidStatus_NotInList()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', 'Foo');

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['account_recovery_user_setting']['status']['inList']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidStatus_Missing()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'nope',
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['account_recovery_user_setting']['status']['_empty']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidKey_NotOpenPGP()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey(
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', 'not openpgp key')
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key']['data']['isValidOpenPGPMessage']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidKey_NotSymmetricBlock()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $gpgData = $this->encrypt($orkFingerprint, $orkArmored);
        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $gpgData)
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpgData,
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key']['data']['hasSymmetricPacketRule']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_NotOpenPGP()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'not openpgp data',
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['data']['isValidOpenPGPMessage']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_NotAsymmetricBlock()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->getDummyPrivateKey(),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['data']['hasAsymmetricPacketRule']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_EmptyData()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['data']['_required']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_WrongRecipient()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        $otherKey = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $otherFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($otherFingerprint, $otherKey),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['data']['wrongRecipient']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_MissingFingerprint()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['recipient_fingerprint']['_required']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_WrongFingerprint()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAB',
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['recipient_fingerprint']['wrongRecipient']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_MissingModel()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['recipient_foreign_model']['_required']);
        }
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_WrongModel()
    {
        $orkArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $orkFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => $orkFingerprint,
                'armored_key' => $orkArmored,
                'deleted' => null,
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => 'SomethingElse',
                'data' => $this->encrypt($orkFingerprint, $orkArmored),
            ]]);

        try {
            (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_user_setting']['account_recovery_private_key_passwords'][0]['recipient_foreign_model']['inList']);
        }
    }
}
