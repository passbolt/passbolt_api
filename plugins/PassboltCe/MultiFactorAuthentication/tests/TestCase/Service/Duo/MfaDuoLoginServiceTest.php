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
 * @since         5.18.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Error\Exception\Duo\DuoSubscriberMismatchException;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoLoginService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MfaDuoLoginService::class)]
class MfaDuoLoginServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use MfaOrgSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = true;
        $this->mockMfaOrgSettings($settings);
    }

    public function testMfaDuoLoginService_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $mfaDuoCallbackDto = $this->createCallbackDto();
        $authToken = $this->persistVerifyToken($user, $mfaDuoCallbackDto);
        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user);

        $service = new MfaDuoLoginService($duoSdkClientMock->getClient());
        $resultAuthToken = $service->login($this->createUac($user), $mfaDuoCallbackDto, $authToken->token);

        $this->assertSame($authToken->id, $resultAuthToken->id);
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
    }

    public function testMfaDuoLoginService_Error_SubscriberMismatchIsNotWrapped(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $mfaDuoCallbackDto = $this->createCallbackDto();
        $authToken = $this->persistVerifyToken($user, $mfaDuoCallbackDto);
        $duoSdkClientMock = DuoSdkClientMock::createWithWrongExchangeAuthorizationCodeFor2FAResultSub(
            $this->getMockBuilder(Client::class),
        );

        $service = new MfaDuoLoginService($duoSdkClientMock->getClient());

        $this->expectException(DuoSubscriberMismatchException::class);
        $this->expectExceptionMessage('the Duo account does not match your passbolt account');
        $service->login($this->createUac($user), $mfaDuoCallbackDto, $authToken->token);
    }

    public function testMfaDuoLoginService_Error_OtherDuoFailureKeepsGenericMessage(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $mfaDuoCallbackDto = $this->createCallbackDto();
        $authToken = $this->persistVerifyToken($user, $mfaDuoCallbackDto);
        $duoSdkClientMock = DuoSdkClientMock::createWithExchangeAuthorizationCodeFor2FAResultThrowingException(
            $this->getMockBuilder(Client::class),
        );

        $service = new MfaDuoLoginService($duoSdkClientMock->getClient());

        try {
            $service->login($this->createUac($user), $mfaDuoCallbackDto, $authToken->token);
            $this->fail('A BadRequestException was expected.');
        } catch (BadRequestException $e) {
            $this->assertNotInstanceOf(DuoSubscriberMismatchException::class, $e);
            $this->assertSame('Unable to verify Duo authentication.', $e->getMessage());
        }
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function createUac(User $user): UserAccessControl
    {
        return new UserAccessControl(Role::USER, $user->id, $user->username);
    }

    private function createCallbackDto(): MfaDuoCallbackDto
    {
        return new MfaDuoCallbackDto([
            'state' => UuidFactory::uuid(),
            'duo_code' => 'not-so-random-duo-code',
        ]);
    }

    private function persistVerifyToken(User $user, MfaDuoCallbackDto $mfaDuoCallbackDto): AuthenticationToken
    {
        /** @var \App\Model\Entity\AuthenticationToken $authToken */
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $mfaDuoCallbackDto->state,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_VERIFY)->persist();

        return $authToken;
    }
}
