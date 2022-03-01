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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\UuidFactory;
use Cake\Http\ServerRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoverySetupCompleteService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoverySetupCompleteServiceTest extends AccountRecoveryTestCase
{
    use GpgAdaSetupTrait;

    /**
     * The user setting status should be valid
     *
     * @see AccountRecoveryUserSettingsTable::validationDefault()
     */
    public function testAccountRecoverySetupCompleteService_InvalidStatus()
    {
        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $user = $token->user;

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', 'Foo');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The status should be one of the following: rejected, approved.');
        (new AccountRecoverySetupCompleteService($request))->complete($user->id);
    }

    /**
     * If the user setting rejects the account recovery, no private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Rejected()
    {
        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $user = $token->user;

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED)
            ->withData('account_recovery_private_key.data', 'Foo');

        (new AccountRecoverySetupCompleteService($request))->complete($user->id);

        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
    }

    /**
     * If the user setting rejects the account recovery, no private key is saved
     */
    public function testAccountRecoverySetupCompleteService_Success()
    {
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);

        $token = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $user = $token->user;

        $request = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $this->getDummyPublicKey())
            ->withData('account_recovery_user_setting.status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED)
            ->withData('account_recovery_private_key.data', $this->getDummyPrivateKey())
            ->withData('account_recovery_private_key_passwords', [[
                'recipient_foreign_key' => UuidFactory::uuid(),
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $this->gpg->encrypt('Foo'),
            ]]);

        (new AccountRecoverySetupCompleteService($request))->complete($user->id);

        $this->assertSame(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(1, AccountRecoveryPrivateKeyPasswordFactory::count());
    }

    public function testAccountRecoverySetupCompleteService_Errors_Left_To_Implement()
    {
        $this->markTestIncomplete('Key is sent but account recovery is not setup (unlikely but still)');
        $this->markTestIncomplete('key is not sent but account recovery is mandatory');
        $this->markTestIncomplete('key backup is sent but is invalid');
        $this->markTestIncomplete('key backup is sent but is not encrypted for ORK');
        $this->markTestIncomplete('key backup is sent but is not encrypted for all recovery contacts');
    }
}
