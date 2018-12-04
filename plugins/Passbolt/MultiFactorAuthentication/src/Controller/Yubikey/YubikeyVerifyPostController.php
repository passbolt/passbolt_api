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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Yubikey;

use App\Error\Exception\CustomValidationException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeyVerifyForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeyVerifyPostController extends MfaVerifyController
{

    /**
     * Yubikey verify post
     *
     * @throws InternalErrorException
     * @throws BadRequestException
     * @return void
     */
    public function post()
    {
        $this->_handleVerifiedNotRequired();
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_YUBIKEY);

        // Verify hotp
        $uac = $this->User->getAccessControl();
        $verifyForm = new YubikeyVerifyForm($uac, $this->mfaSettings);
        try {
            $verifyForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            $this->request = $this->request
                ->withData('hotp', '');

            if ($this->request->is('json')) {
                throw $exception;
            }
            // Display form with error msg
            $this->set('providers', $this->mfaSettings->getEnabledProviders());
            $this->set('verifyForm', $verifyForm);
            $this->viewBuilder()
                ->setLayout('mfa_verify')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_YUBIKEY))
                ->setTemplate('verifyForm');

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $this->_generateMfaToken(MfaSettings::PROVIDER_YUBIKEY);
        $this->_handleVerifySuccess();
    }
}
