<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Yubikey;

use App\Error\Exception\CustomValidationException;
use Passbolt\MultiFactorAuthentication\Form\YubikeySetupForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;

class YubikeySetupPostController extends MfaSetupController
{
    /**
     * Handle Yubikey setup POST request
     * @return void
     */
    public function post()
    {
        $this->orgAllowProviderOrFail(MfaSettings::PROVIDER_YUBIKEY);
        $this->notAlreadySetupOrFail(MfaSettings::PROVIDER_YUBIKEY);

        $uac = $this->User->getAccessControl();
        $setupForm = new YubikeySetupForm($uac);
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
                    ->setTemplatePath('Totp')
                    ->setTemplate('setupForm');
            }

            return;
        }
        $this->_handlePostSuccess(MfaSettings::PROVIDER_YUBIKEY);
    }
}
