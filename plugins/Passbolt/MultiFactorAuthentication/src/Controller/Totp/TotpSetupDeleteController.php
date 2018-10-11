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
use Cake\Network\Exception\ForbiddenException;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class TotpSetupDeleteController extends AppController
{
    /**
     * Totp Get Qr Code and provisioning urls
     *
     * @return void
     */
    public function delete()
    {
        $uac = $this->User->getAccessControl();
        try {
            $mfaSettings = MfaSettings::get($uac);
        } catch(RecordNotFoundException $exception) {
            $this->success('No TOTP configuration found. Nothing to delete.');
            return;
        }

        // One need to have an active mfa token to disable it
        $mfa = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (!isset($mfa) || !MfaVerifiedToken::check($uac, $mfa)) {
            throw new ForbiddenException(__('MFA verification required.'));
        }

        // Disable provider
        $mfaSettings->disableProvider(MfaSettings::PROVIDER_OTP);
        MfaVerifiedToken::deleteAll($uac);

        // Clear any existing cookie if mfa check required
        $this->response = $this->response
            ->withCookie(MfaVerifiedCookie::clearCookie($this->request->is('ssl')));

        $this->success('The TOTP configuration was deleted.');
    }

}
