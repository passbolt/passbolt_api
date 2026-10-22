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
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyBeginPostController extends MfaVerifyController
{
    /**
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service.
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember-me policy.
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService $webauthnStartAssertionService Start service.
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting,
        MfaWebauthnStartAssertionService $webauthnStartAssertionService,
    ): void {
        $this->_assertRequestIsJson();
        $this->_handleVerifiedNotRequired($sessionIdentificationService, $rememberMeForAMonthSetting);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_WEBAUTHN);

        $payload = $webauthnStartAssertionService->start($this->User->getAccessControl());
        $this->success(__('The operation was successful.'), $payload);
    }
}
