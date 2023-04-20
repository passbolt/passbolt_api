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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Totp;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\CustomValidationException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpVerifyPostController extends MfaVerifyController
{
    /**
     * Totp Verify Post
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $verifyForm MFA Form
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting.
     * @throws \Cake\Http\Exception\InternalErrorException
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $verifyForm,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ) {
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_TOTP);

        // Verify totp
        try {
            $verifyForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            }
            // Display form with error msg
            $this->set('providers', $this->mfaSettings->getEnabledProviders());
            $this->set('verifyForm', $verifyForm);
            $this->set('isRememberMeForAMonthEnabled', $rememberMeForAMonthSetting->isEnabled());
            $this->viewBuilder()
                ->setLayout('mfa_verify')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_TOTP))
                ->setTemplate('verifyForm');

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $this->_generateMfaToken(
            MfaSettings::PROVIDER_TOTP,
            $sessionIdentificationService,
            $rememberMeForAMonthSetting
        );
        $this->_handleVerifySuccess();
    }
}
