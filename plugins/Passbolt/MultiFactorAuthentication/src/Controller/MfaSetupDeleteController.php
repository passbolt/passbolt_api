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
namespace Passbolt\MultiFactorAuthentication\Controller;

use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;
use Cake\Network\Exception\ForbiddenException;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaSetupDeleteController extends MfaController
{
    /**
     * Delete a provider setting
     *
     * @return void
     */
    protected function _handleDelete(string $provider)
    {
        $uac = $this->User->getAccessControl();
        if ($this->mfaSettings->getAccountSettings() === null) {
            $this->success('No configuration found for this provider. Nothing to delete.');
            return;
        }

        // One need to have an active mfa token to disable it
        $mfa = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (!isset($mfa) || !MfaVerifiedToken::check($uac, $mfa)) {
            throw new ForbiddenException(__('MFA verification required.'));
        }

        // Disable provider
        $this->mfaSettings->getAccountSettings()->disableProvider($provider);
        $this->success('The configuration was deleted.');
    }
}