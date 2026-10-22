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
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupFinishPostController extends MfaSetupController
{
    /**
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service.
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $webauthnSetupForm Form bound by InjectMfaFormMiddleware.
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $webauthnSetupForm,
    ): void {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        $webauthnSetupForm->execute($this->request->getData());
        $this->_handlePostSuccess(MfaSettings::PROVIDER_WEBAUTHN, $sessionIdentificationService);
    }
}
