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
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
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

        (new Plugin())->addAssociations();
    }

    /**
     * Utility function to speed up encryption step in test cases
     *
     * @param $fingerprint
     * @param $key
     * @return string
     */
    private function encrypt(string $fingerprint, string $key): string
    {
        // Build the data
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->clearKeys();
        $this->gpg->importKeyIntoKeyring($key);
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);

        return $this->gpg->encrypt('Foo');
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
     * If the user setting rejects the account recovery, no private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Success_OptinApproved()
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
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
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
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
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
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => 'nope',
            ]]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Account recovery is disabled.');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_OptinRejectedWithData()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
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
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => 'nope',
            ]]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Invalid request');
        (new AccountRecoverySetupCompleteService($request))->complete($token->user->id);
    }

    public function testAccountRecoverySetupCompleteService_Errors_MandatoryStatusRejected()
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
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
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
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $this->serverKeyId,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
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
            ->withData('account_recovery_user_setting.account_recovery_private_key.data', 'not openpgp key')
            ->withData('account_recovery_user_setting.account_recovery_private_key_passwords', [[
                'recipient_fingerprint' => $orkFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
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
        $this->markTestIncomplete('Not openpgp symmetric message');
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPassword_NotOpenPGP()
    {
        $this->markTestIncomplete('Not openpgp message');
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPassword_NotAsymmetricBlock()
    {
        $this->markTestIncomplete('Not openpgp symmetric message');
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_NotOpenPGP()
    {
        $this->markTestIncomplete('Not openpgp message');
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_NotAsymetricMessage()
    {
        $this->markTestIncomplete('Not openpgpg asymmetric message');
    }

    public function testAccountRecoverySetupCompleteService_Errors_InvalidPasswords_WrongRecipient()
    {
        $this->markTestIncomplete('Recipient is not ORK');
    }
}
