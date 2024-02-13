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
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\ServiceUnavailableException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStartDuoAuthenticationService;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;

class MfaDuoStartDuoAuthenticationServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testMfaDuoStartDuoAuthenticationService_Success()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, 'duo@test.test');

        $mock = DuoSdkClientMock::createDefault($this, $user);
        $service = new MfaDuoStartDuoAuthenticationService(AuthenticationToken::TYPE_MFA_SETUP, $mock->getClient());
        $mfaDuoAuthenticationRequestDto = $service->start($uac);

        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertNotNull($mfaDuoAuthenticationRequestDto->authenticationToken);
        $this->assertEquals(AuthenticationToken::TYPE_MFA_SETUP, $mfaDuoAuthenticationRequestDto->authenticationToken->type);
        $this->assertEquals($user->id, $mfaDuoAuthenticationRequestDto->authenticationToken->user_id);
        $this->assertNotNull($mfaDuoAuthenticationRequestDto->duoAuthenticationUrl);
        // Assert that the mock function was called to generate the authentication url.
        $this->assertEquals($mock->duoAuthUrl, $mfaDuoAuthenticationRequestDto->duoAuthenticationUrl);
    }

    public function testMfaDuoStartDuoAuthenticationService_Success_WithRedirect()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, 'duo@test.test');
        $redirectPath = '/redirect/path';
        $duoSdkClientMock = DuoSdkClientMock::createDefault($this, $user)->getClient();
        $service = new MfaDuoStartDuoAuthenticationService(AuthenticationToken::TYPE_MFA_SETUP, $duoSdkClientMock);
        $mfaDuoAuthenticationRequestDto = $service->start($uac, $redirectPath);

        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertNotNull($mfaDuoAuthenticationRequestDto->authenticationToken);
        $this->assertEquals(AuthenticationToken::TYPE_MFA_SETUP, $mfaDuoAuthenticationRequestDto->authenticationToken->type);
        $this->assertEquals($user->id, $mfaDuoAuthenticationRequestDto->authenticationToken->user_id);
        $authenticationTokenData = json_decode($mfaDuoAuthenticationRequestDto->authenticationToken->data, true);
        $this->assertEquals($redirectPath, $authenticationTokenData['redirect']);
        $this->assertNotNull($mfaDuoAuthenticationRequestDto->duoAuthenticationUrl);
    }

    public function testMfaDuoStartDuoAuthenticationService_Error_MisconfiguredDuoProvider()
    {
        try {
            new MfaDuoStartDuoAuthenticationService(AuthenticationToken::TYPE_MFA_SETUP);
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(ServiceUnavailableException::class, $th);
        $this->assertTextContains('Could not enable Duo MFA provider.', $th->getMessage());
    }

    public function testMfaDuoStartDuoAuthenticationService_Error_DuoServiceHealthcheckError()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoSdkClientMock = (new DuoSdkClientMock($this))->mockErrorHealthCheck()->getClient();
        $service = new MfaDuoStartDuoAuthenticationService(AuthenticationToken::TYPE_MFA_SETUP, $duoSdkClientMock);
        try {
            $service->start($uac);
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(ServiceUnavailableException::class, $th);
        $this->assertTextContains('Unable to connect to Duo services.', $th->getMessage());
    }

    public function testMfaDuoStartDuoAuthenticationService_Error_InvalidDuoCallbackAuthenticationType()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoSdkClientMock = DuoSdkClientMock::createDefault($this, $user)->getClient();
        $service = new MfaDuoStartDuoAuthenticationService('invalid-token-type', $duoSdkClientMock);

        try {
            $service->start($uac);
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(\InvalidArgumentException::class, $th);
        $this->assertTextContains('The authentication token type should be one of the following:', $th->getMessage());
    }

    public function testMfaDuoStartDuoAuthenticationService_Error_DuoAuthenticationUrlException()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoSdkClientMock = (new DuoSdkClientMock($this))->mockErrorCreateAuthUrl()->getClient();
        $service = new MfaDuoStartDuoAuthenticationService(AuthenticationToken::TYPE_MFA_SETUP, $duoSdkClientMock);

        try {
            $service->start($uac);
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(InternalErrorException::class, $th);
        $this->assertTextContains('Unable to create the Duo authentication URL.', $th->getMessage());
    }
}
