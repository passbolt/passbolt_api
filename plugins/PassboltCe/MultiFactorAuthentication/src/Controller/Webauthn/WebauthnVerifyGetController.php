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
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyGetController extends MfaVerifyController
{
    /**
     * Render the top-level authentication ceremony page. Because the passbolt MFA verify page is
     * already served top-level from the passbolt origin (its responses set frame-ancestors 'none'),
     * navigator.credentials.get() runs with the correct relying party id without any interstitial.
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session id service
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting
     * @return void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ): void {
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_WEBAUTHN);

        if ($this->getRequest()->is('json')) {
            $this->success(__('Start the authentication using the begin endpoint.'));

            return;
        }

        $this->set('providers', $this->mfaSettings->getEnabledProviders());
        $this->set('isRememberMeForAMonthEnabled', $rememberMeForAMonthSetting->isEnabled());
        $this->set('csrfToken', $this->getRequest()->getAttribute('csrfToken'));
        $this->viewBuilder()
            ->setLayout('mfa_verify')
            ->setTemplatePath('Webauthn')
            ->setTemplate('verify');
    }
}
