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
namespace Passbolt\MultiFactorAuthentication\Controller\Totp;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use Passbolt\MultiFactorAuthentication\Form\TotpSetupForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class TotpSetupPostController extends AppController
{
    public function post()
    {
        $uac = $this->User->getAccessControl();
        $totpSetupForm = new TotpSetupForm($uac);
        try {
            $totpSetupForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            } else {
                $this->set('totpSetupForm', $totpSetupForm);
                $this->set('theme', $this->User->theme());
                $this->request = $this->request
                    ->withData('otpQrCodeImage', $this->request->getData('otpQrCodeImage'));
                $this->viewBuilder()
                    ->setLayout('totp_setup')
                    ->setTemplatePath('Totp')
                    ->setTemplate('setupForm');
            }

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_OTP);
        $remember = ($this->request->getData('remember') !== null);
        $cookie = MfaVerifiedCookie::get($token, $remember, $this->request->is('ssl'));
        $this->response = $this->response->withCookie($cookie);

        if (!$this->request->is('json')) {
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('totp_setup')
                ->setTemplatePath('Totp')
                ->setTemplate('setupSuccess');
        }

        $mfaSettings = MfaSettings::get($uac);
        $this->success(__('Multi Factor Authentication is configured!'), [
            'created' => $mfaSettings->getCreated(),
            'modified' => $mfaSettings->getModified()
        ]);
    }
}
