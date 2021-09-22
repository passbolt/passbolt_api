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
namespace Passbolt\MultiFactorAuthentication\Controller\Yubikey;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\CustomValidationException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupPostController extends MfaSetupController
{
    /**
     * Handle Yubikey setup POST request
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    public function post(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $setupForm
    ) {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_YUBIKEY);
        $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_YUBIKEY);

        try {
            $setupForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            } else {
                $this->set('yubikeySetupForm', $setupForm);
                $this->set('theme', $this->User->theme());
                $this->viewBuilder()
                    ->setLayout('mfa_setup')
                    ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_YUBIKEY))
                    ->setTemplate('setupForm');
            }

            return;
        }
        $this->_handlePostSuccess(MfaSettings::PROVIDER_YUBIKEY, $sessionIdentificationService);
    }
}
