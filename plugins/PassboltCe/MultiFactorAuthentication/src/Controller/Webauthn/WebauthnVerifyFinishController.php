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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyFinishController extends MfaVerifyController
{
    /**
     * Finish a WebAuthn authentication ceremony: validate the assertion, bump the signature counter
     * and, on success, issue the MFA verified token and cookie.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService $service Assertion verification service
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session id service
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting
     * @return void
     */
    public function post(
        MfaWebauthnVerifyAssertionService $service,
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ): void {
        $this->_assertRequestIsJson();
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_WEBAUTHN);

        $token = $this->request->getData('token');
        if (!is_string($token) || $token === '') {
            throw new BadRequestException(__('A challenge token is required.'));
        }

        $service->verify($this->User->getAccessControl(), $token, $this->extractCredentialJson());

        $this->_generateMFaToken(
            MfaSettings::PROVIDER_WEBAUTHN,
            $sessionIdentificationService,
            $rememberMeForAMonthSetting
        );
        $this->_handleVerifySuccess();
    }

    /**
     * Extract the raw JSON credential from the request.
     *
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException If the credential is missing
     */
    protected function extractCredentialJson(): string
    {
        $credential = $this->request->getData('credential');
        if (is_string($credential) && $credential !== '') {
            return $credential;
        }
        if (is_array($credential)) {
            return (string)json_encode($credential);
        }

        throw new BadRequestException(__('An authentication response is required.'));
    }
}
