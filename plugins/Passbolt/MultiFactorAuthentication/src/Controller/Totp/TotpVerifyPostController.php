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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Form\TotpVerifyForm;
use App\Model\Entity\AuthenticationToken;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class TotpVerifyPostController extends AppController
{

    /**
     * @throws InternalErrorException
     * @throws BadRequestException
     */
    public function post()
    {
        // Check if settings exists
        $uac = $this->User->getAccessControl();
        try {
            $mfaSettings = MfaSettings::get($uac);
        } catch(RecordNotFoundException $exception) {
            // for example mfa config was deleted between mfa middleware redirect and here
            throw new InternalErrorException(__('No valid TOTP settings found.'));
        }
        if (!$mfaSettings->isReadyToUse(MfaSettings::PROVIDER_OTP)) {
            // for example a user is trying to force a check
            throw new BadRequestException(__('Incomplete TOTP settings found.'));
        }

        // Verify totp
        $totpVerifyForm = new TotpVerifyForm($uac, $mfaSettings);
        try {
            $totpVerifyForm->execute($this->request->getData());
        } catch(CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            }
            // Display form with error msg
            $this->set('totpVerifyForm', $totpVerifyForm);
            $this->viewBuilder()
                ->setLayout('totp_verify')
                ->setTemplatePath('Totp')
                ->setTemplate('verifyForm');

            return;
        }

        // Build verified proof token and associated cookie and add it to request
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_OTP);
        $remember = ($this->request->getData('remember') !== null);
        $cookie = MfaVerifiedCookie::get($token, $remember, $this->request->is('ssl'));
        $this->response = $this->response->withCookie($cookie);

        // Success response depends on request type
        if ($this->request->is('json')) {
            $this->success(__('The OTP verification was a success.'));
        } else {
            $this->redirect(Router::url($this->request->getQuery('redirect')));
        }
    }
}
