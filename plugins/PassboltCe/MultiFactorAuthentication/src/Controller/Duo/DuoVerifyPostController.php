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
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\CustomValidationException;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyPostController extends MfaVerifyController
{
    /**
     * Duo Verify Post
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
        if ($this->request->is('json')) {
            throw new BadRequestException(__('This functionality is not available using AJAX/JSON.'));
        }
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_DUO);

        // Verify totp
        try {
            $verifyForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            $this->redirect('/mfa/verify/duo');

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $this->_generateMfaToken(
            MfaSettings::PROVIDER_DUO,
            $sessionIdentificationService,
            $rememberMeForAMonthSetting
        );
        $this->_handleVerifySuccess();
    }
}
