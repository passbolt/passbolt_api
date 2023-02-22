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
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoEnableService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaDuoEnableServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use MfaOrgSettingsTestTrait;

    public function testMfaDuoEnableService_Success()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mfaDuoCallbackDto = new MfaDuoCallbackDto([
            'state' => UuidFactory::uuid(),
            'duo_code' => 'not-so-random-duo-code',
        ]);
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $mfaDuoCallbackDto->state,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $duoSdkClientMock = DuoSdkClientMock::createDefault($this, $user)->getClient();

        $service = new MfaDuoEnableService($duoSdkClientMock);
        $resultAuthToken = $service->enable($uac, $mfaDuoCallbackDto, $authToken->token);

        // Assert the service returns the authentication token.
        $this->assertInstanceOf(AuthenticationToken::class, $resultAuthToken);
        $this->assertEquals($authToken->id, $resultAuthToken->id);
        // Assert the token was consumed.
        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
        // Assert Duo was enabled for the user.
        $this->assertEquals(1, AccountSettingFactory::count());
        $this->assertTrue(MfaAccountSettings::get($uac)->isProviderReady(MfaSettings::PROVIDER_DUO));
    }

    public function testMfaDuoEnableService_Error_CannotValidateDuoCallbackAuthenticationToken()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mfaDuoCallbackDto = new MfaDuoCallbackDto([
            'state' => UuidFactory::uuid(),
            'duo_code' => 'not-so-random-duo-code',
        ]);
        AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $mfaDuoCallbackDto->state,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $duoSdkClientMock = DuoSdkClientMock::createDefault($this, $user)->getClient();

        $service = new MfaDuoEnableService($duoSdkClientMock);
        try {
            $service->enable($uac, $mfaDuoCallbackDto, UuidFactory::uuid());
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $th);
        $this->assertTextContains('The token should reference an active Duo callback authentication token.', $th->getMessage());

        // Assert the good token was not consumed.
        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertCount(1, AuthenticationTokenFactory::find()->where(['active' => true]));
        // Assert Duo was not enabled for the user.
        $this->assertEquals(0, AccountSettingFactory::count());
    }

    public function testMfaDuoEnableService_Error_CannotVerifyDuoCode()
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $mfaDuoCallbackDto = new MfaDuoCallbackDto([
            'state' => UuidFactory::uuid(),
            'duo_code' => 'not-so-random-duo-code',
        ]);
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $mfaDuoCallbackDto->state,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $duoSdkClientMock = DuoSdkClientMock::createWithExchangeAuthorizationCodeFor2FAResultThrowingException($this);

        $service = new MfaDuoEnableService($duoSdkClientMock->getClient());

        try {
            $service->enable($uac, $mfaDuoCallbackDto, $authToken->token);
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(BadRequestException::class, $th);
        $this->assertTextContains('Unable to verify Duo authentication.', $th->getMessage());

        // Assert the good token was consumed.
        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
        // Assert Duo was not enabled for the user.
        $this->assertEquals(0, AccountSettingFactory::count());
    }
}
