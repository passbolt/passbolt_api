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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnDelegatedCallbackPostController extends MfaVerifyController
{
    /**
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service.
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember-me policy.
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService $delegatedTokenService Delegated token service.
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting,
        MfaWebauthnDelegatedTokenService $delegatedTokenService,
    ): void {
        $this->_assertRequestIsJson();
        $this->_handleVerifiedNotRequired($sessionIdentificationService, $rememberMeForAMonthSetting);

        $delegatedToken = $this->getRequest()->getData('mfa_delegated_token');
        $delegatedSecret = $this->getRequest()->getData('mfa_delegated_secret');

        $this->assertDelegatedToken($delegatedToken);
        $this->assertDelegatedSecret($delegatedSecret);

        $delegatedTokenService->consume($this->User->getAccessControl(), $delegatedToken, $delegatedSecret);

        $this->_generateMFaToken(
            MfaSettings::PROVIDER_WEBAUTHN,
            $sessionIdentificationService,
            $rememberMeForAMonthSetting,
        );
        $this->_handleVerifySuccess();
    }

    /**
     * @param mixed $delegatedToken The delegated token value to assert.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If token value is invalid
     */
    private function assertDelegatedToken(mixed $delegatedToken): void
    {
        if (!is_string($delegatedToken)) {
            throw new BadRequestException(__('The delegated token is not valid.'));
        }
    }

    /**
     * @param mixed $delegatedSecret The delegated secret value to assert.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If secret value is invalid
     */
    private function assertDelegatedSecret(mixed $delegatedSecret): void
    {
        if (!is_string($delegatedSecret) || !Validation::uuid($delegatedSecret)) {
            throw new BadRequestException(__('The delegated secret is not valid.'));
        }
    }
}
