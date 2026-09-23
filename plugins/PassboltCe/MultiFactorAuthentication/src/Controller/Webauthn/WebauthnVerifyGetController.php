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
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyGetController extends MfaVerifyController
{
    /**
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service.
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember-me policy.
     * @return void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting,
    ): void {
        $this->_handleVerifiedNotRequired($sessionIdentificationService, $rememberMeForAMonthSetting);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_WEBAUTHN);

        if ($this->request->is('json')) {
            $this->success(__('The operation was successful.'), [
                'providers' => $this->mfaSettings->getEnabledProviders(),
                'isRememberMeForAMonthEnabled' => $rememberMeForAMonthSetting->isEnabled(),
            ]);

            return;
        }

        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_WEBAUTHN))
            ->setTemplate('verifyForm');
    }
}
