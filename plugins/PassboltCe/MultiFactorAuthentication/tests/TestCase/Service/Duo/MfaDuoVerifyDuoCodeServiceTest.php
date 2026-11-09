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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\UnauthorizedException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Error\Exception\Duo\DuoSubscriberMismatchException;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoVerifyDuoCodeService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Throwable;

class MfaDuoVerifyDuoCodeServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use MfaOrgSettingsTestTrait;

    public function testMfaDuoVerifyDuoCodeService_Success()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $duoSdkClientMock);
        $verified = $service->verify($uac, $duoCode);

        $this->assertTrue($verified);
    }

    public function testMfaDuoVerifyDuoCodeService_Error_CannotRetrieveAuthenticationDetails()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        $duoSdkClientMock = DuoSdkClientMock::createWithExchangeAuthorizationCodeFor2FAResultThrowingException($this->getMockBuilder(Client::class));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $duoSdkClientMock->getClient());

        try {
            $service->verify($uac, $duoCode);
        } catch (Throwable $th) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $th);
        $this->assertTextContains('Unable to verify Duo code against Duo service', $th->getMessage());
    }

    public function testMfaDuoVerifyDuoCodeService_Error_CallbackWrongIss()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        $mock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultIss($this->getMockBuilder(Client::class), $user);
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());

        try {
            $service->verify($uac, $duoCode);
        } catch (Throwable $th) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $th);
        $this->assertTextContains('The duo authentication origin endpoint does not match the organization setting duo hostname.', $th->getMessage());
    }

    public function testMfaDuoVerifyDuoCodeService_Success_CallbackWrongUsername_Without_Config()
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        $duoSdkClientMock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $duoSdkClientMock->getClient());
        $verified = $service->verify($uac, $duoCode);

        $this->assertTrue($verified);
    }

    public function testMfaDuoVerifyDuoCodeService_Success_CallbackUsernameWithCaseDifference_With_Verify_Config_Enabled()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, true);

        $duoSdkClientMock = DuoSdkClientMock::createWithExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class), mb_strtoupper($user->username));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $duoSdkClientMock->getClient());
        $verified = $service->verify($uac, $duoCode);
        $this->assertTrue($verified);
    }

    public function testMfaDuoVerifyDuoCodeService_Error_CallbackWrongUsername_With_Verify_Config_Enabled()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $duoCode = 'not-so-random-duo-code';

        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, true);

        $duoSdkClientMock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $duoSdkClientMock->getClient());

        try {
            $service->verify($uac, $duoCode);
        } catch (Throwable $th) {
        }

        $this->assertInstanceOf(DuoSubscriberMismatchException::class, $th);
        $this->assertTextContains('the Duo account does not match your passbolt account', $th->getMessage());
    }

    public function testMfaDuoVerifyDuoCodeService_Error_CallbackWrongSub_StoredVerifySubscriberValueIsTrue(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        $this->mockOrgSettingsWithVerifySubscriber(true);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $mock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());

        $this->expectException(DuoSubscriberMismatchException::class);
        $service->verify($uac, 'not-so-random-duo-code');
    }

    public function testMfaDuoVerifyDuoCodeService_Success_CallbackWrongSub_DBValueWinsOverLegacyTrue(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, true);
        $this->mockOrgSettingsWithVerifySubscriber(false);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));

        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());
        $result = $service->verify($uac, 'not-so-random-duo-code');

        $this->assertTrue($result);
    }

    public function testMfaDuoVerifyDuoCodeService_Success_CallbackSubWithCaseDifference(): void
    {
        $this->mockOrgSettingsWithVerifySubscriber(true);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mock = DuoSdkClientMock::createWithExchangeAuthorizationCodeFor2FAResultSub(
            $this->getMockBuilder(Client::class),
            mb_strtoupper($user->username),
        );

        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());
        $result = $service->verify($uac, 'not-so-random-duo-code');

        $this->assertTrue($result);
    }

    public function testMfaDuoVerifyDuoCodeService_Error_CallbackWithoutSub_SubscriberVerificationValueIsTrue(): void
    {
        $this->mockOrgSettingsWithVerifySubscriber(true);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $mock = DuoSdkClientMock::createWithoutExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));
        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());

        $this->expectException(DuoSubscriberMismatchException::class);
        $service->verify($uac, 'not-so-random-duo-code');
    }

    public function testMfaDuoVerifyDuoCodeService_Success_CallbackWithoutSub_SubscriberVerificationValueIsFalse(): void
    {
        $this->mockOrgSettingsWithVerifySubscriber(false);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mock = DuoSdkClientMock::createWithoutExchangeAuthorizationCodeFor2FAResultSub($this->getMockBuilder(Client::class));

        $service = new MfaDuoVerifyDuoCodeService(AuthenticationToken::TYPE_MFA_VERIFY, $mock->getClient());
        $result = $service->verify($uac, 'not-so-random-duo-code');

        $this->assertTrue($result);
    }

    private function mockOrgSettingsWithVerifySubscriber(bool $verifySubscriber): void
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = $verifySubscriber;
        $this->mockMfaOrgSettings($settings);
    }
}
