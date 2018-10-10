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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Form\TotpVerifyForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class TotpVerifyGetController extends AppController
{

    /**
     * @throws InternalErrorException
     * @throws BadRequestException
     */
    public function get()
    {
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

        // Mfa cookie is set and a valid token
        $mfaVerifiedToken = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfaVerifiedToken)) {
            if(MfaVerifiedToken::check($uac, $mfaVerifiedToken)) {
                if ($this->request->is('json')) {
                    throw new BadRequestException(__('MFA is not required.'));
                } else {
                    $this->redirect('/');
                    return;
                }
            }
        }

        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        $totpVerifyForm = new TotpVerifyForm($uac, $mfaSettings);

        if (!$this->request->is('json')) {
            $this->set('totpVerifyForm', $totpVerifyForm);
            $this->viewBuilder()
                ->setLayout('totp_verify')
                ->setTemplatePath('Totp')
                ->setTemplate('verifyForm');
        } else {
            $this->success(__('Please provide the one time password.'));
        }
    }
}
