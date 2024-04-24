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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Authenticator\AbstractSessionIdentificationService;
use App\Authenticator\SessionIdentificationServiceInterface;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthTestTrait;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaIntegrationTestCase extends AppIntegrationTestCase
{
    use JwtAuthTestTrait;
    use MfaAccountSettingsTestTrait;
    use MfaOrgSettingsTestTrait;
    use UserAccessControlTrait;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('MultiFactorAuthentication');
        $this->enableFeaturePlugin('JwtAuthentication');
        MfaSettings::clear();
    }

    /**
     * @param UserAccessControl $uac UAC
     * @param string|null $provider provider
     * @param bool|null $remember remember
     * @param string|null $sessionId session ID
     * @return string The valid mfa token
     */
    public function mockMfaCookieValid(
        UserAccessControl $uac,
        ?string $provider = null,
        ?bool $remember = false,
        ?string $sessionId = ''
    ): string {
        if (!isset($provider)) {
            throw new InternalErrorException('Cannot mock mfa verification without provider.');
        }
        $sessionId = empty($sessionId) ? uniqid() : $sessionId;
        $this->mockSessionId($sessionId);
        $token = MfaVerifiedToken::get($uac, $provider, $sessionId, $remember);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $token);

        return $token;
    }

    /**
     * Injects in the DIC a MFA form stub, returning $validate to validate()
     *
     * @param string $className MFA Form class name
     * @param UserAccessControl $uac UAC
     * @param bool $validate value returned from form validate()
     */
    public function mockMfaFormInterface(string $className, UserAccessControl $uac, bool $validate)
    {
        $stub = $this->getMockBuilder($className)
            ->setConstructorArgs([$uac, MfaSettings::get($uac)])
            ->onlyMethods(['validate',])
            ->getMock();
        $stub->method('validate')->willReturn($validate);

        $this->mockService(MfaFormInterface::class, function () use ($stub) {
            return $stub;
        });
    }

    public function mockValidMfaFormInterface(string $className, UserAccessControl $uac): void
    {
        $this->mockMfaFormInterface($className, $uac, true);
    }

    public function mockInvalidMfaFormInterface(string $className, UserAccessControl $uac): void
    {
        $this->mockMfaFormInterface($className, $uac, false);
    }

    /**
     * Injects in the DIC a session identification Interface with the provided ID.
     * In Session, will return the session ID
     * In JWT, will return the access token
     * In JWT refresh token, will return the hashed access token associated to the refresh token
     *
     * @param string $sessionId Session Id to mock
     * @return void
     */
    public function mockSessionId(string $sessionId)
    {
        $this->mockService(SessionIdentificationServiceInterface::class, function () use ($sessionId) {
            $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
            $stubSessionIdentifier->method('getSessionIdentifier')->willReturn($sessionId);

            return $stubSessionIdentifier;
        });
    }
}
