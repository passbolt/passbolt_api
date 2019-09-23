<?php
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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Totp;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpVerifyForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpVerifyGetController extends MfaVerifyController
{
    /**
     * TOTP Verify Get
     *
     * @throws InternalErrorException if there is no MFA settings for the user
     * @throws BadRequestException if valid Verification token is already present in cookie
     * @throws BadRequestException if there is no MFA settings for this provider
     * @return void
     */
    public function get()
    {
        $this->_handleVerifiedNotRequired();
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_TOTP);

        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        $uac = $this->User->getAccessControl();
        $verifyForm = new TotpVerifyForm($uac, MfaSettings::get($uac));

        if (!$this->request->is('json')) {
            $this->set('providers', $this->mfaSettings->getEnabledProviders());
            $this->set('verifyForm', $verifyForm);
            $this->viewBuilder()
                ->setLayout('mfa_verify')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_TOTP))
                ->setTemplate('verifyForm');
        } else {
            $this->success(__('Please provide the one time password.'));
        }
    }
}
