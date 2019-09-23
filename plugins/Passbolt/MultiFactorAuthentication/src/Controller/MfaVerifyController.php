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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\Date;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaVerifyController extends MfaController
{
    /**
     * Trigger a redirect if MFA verification is not required
     *
     * @throws BadRequestException if valid Verification token is already present in cookie
     * @return void
     */
    protected function _handleVerifiedNotRequired()
    {
        // Mfa cookie is set and a valid token
        $uac = $this->User->getAccessControl();
        $mfaVerifiedToken = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $sessionId = $this->getRequest()->getSession()->id();
        if (isset($mfaVerifiedToken)) {
            if (MfaVerifiedToken::check($uac, $mfaVerifiedToken, $sessionId)) {
                throw new BadRequestException(__('MFA is not required.'));
            }
        }
    }

    /**
     * Trigger an error if current MFA settings do not allow verify for the given provider
     *
     * @throws InternalErrorException if there is no MFA settings for the user
     * @throws BadRequestException if there is no MFA settings for this provider
     * @param string $provider name of the provider
     * @return void
     */
    protected function _handleInvalidSettings(string $provider)
    {
        if ($this->mfaSettings->getAccountSettings() === null) {
            throw new InternalErrorException(__('No valid MFA settings found.'));
        }
        if (!$this->mfaSettings->isProviderEnabled($provider)) {
            // for example a user is trying to force a check on a provider that is not set for the org
            throw new BadRequestException(__('No valid MFA settings found for this provider.'));
        }
    }

    /**
     * Generate MFA verification token and cookie and decorate response accordingly
     *
     * @param string $provider name of the provider
     * @return void
     */
    protected function _generateMFaToken(string $provider)
    {
        $uac = $this->User->getAccessControl();
        $sessionId = $this->getRequest()->getSession()->id();
        $token = MfaVerifiedToken::get($uac, $provider, $sessionId, (bool)$this->request->getData('remember') ?? false);
        $expiryAt = $this->request->getData('remember') ?
            (new Date())->addDays(MfaVerifiedCookie::MAX_DURATION_IN_DAYS) :
            null;
        $secure = Configure::read('passbolt.security.cookies.secure') || $this->getRequest()->is('ssl');
        $cookie = MfaVerifiedCookie::get($token, $expiryAt, $secure);
        $this->response = $this->response->withCookie($cookie);
    }

    /**
     * @return void
     */
    protected function _handleVerifySuccess()
    {
        // Success response depends on request type
        if ($this->request->is('json')) {
            $this->success(__('The multi-factor authentication was a success.'));
        } else {
            $redirectLoop = '/mfa/verify';
            $redirect = $this->request->getQuery('redirect');
            if (is_null($redirect) || substr($redirect, 0, strlen($redirectLoop)) === $redirectLoop) {
                $redirect = '/';
            }
            $this->redirect(Router::url($redirect, true));
        }
    }
}
