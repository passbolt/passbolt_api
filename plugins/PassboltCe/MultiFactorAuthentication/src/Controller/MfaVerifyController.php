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
namespace Passbolt\MultiFactorAuthentication\Controller;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenDate;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

/**
 * @property \App\Controller\Component\SanitizeUrlComponent $SanitizeUrl
 */
abstract class MfaVerifyController extends MfaController
{
    /**
     * @return void
     * @throws \Exception if loadComponent failed
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('SanitizeUrl');
    }

    /**
     * Before render.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);
        $redirect = $this->SanitizeUrl->sanitizeRedirect('/mfa/verify');
        $this->set('redirect', $redirect);
    }

    /**
     * Trigger a redirect if MFA verification is not required
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @throws \Cake\Http\Exception\BadRequestException if valid Verification token is already present in cookie
     * @return void
     */
    protected function _handleVerifiedNotRequired(SessionIdentificationServiceInterface $sessionIdentificationService)
    {
        // Mfa cookie is set and a valid token
        $uac = $this->User->getAccessControl();
        $mfaVerifiedToken = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfaVerifiedToken)) {
            if (MfaVerifiedToken::check($uac, $mfaVerifiedToken, $sessionIdentificationService, $this->getRequest())) {
                throw new BadRequestException(__('The multi-factor authentication is not required.'));
            }
        }
    }

    /**
     * Trigger an error if current MFA settings do not allow verify for the given provider
     * Redirect to password workspace if not JSON
     *
     * @throws \Cake\Http\Exception\InternalErrorException if there is no MFA settings for the user
     * @throws \Cake\Http\Exception\BadRequestException if there is no MFA settings for this provider
     * @param string $provider name of the provider
     * @return \Cake\Http\Response|void
     */
    protected function _handleInvalidSettings(string $provider)
    {
        if ($this->mfaSettings->getAccountSettings() === null) {
            if ($this->getRequest()->is('json')) {
                throw new InternalErrorException('No valid multi-factor authentication settings found.');
            } else {
                return $this->redirect('/');
            }
        }
        if (!$this->mfaSettings->isProviderEnabled($provider)) {
            // for example a user is trying to force a check on a provider that is not set for the org
            if ($this->getRequest()->is('json')) {
                throw new BadRequestException(
                    __('No valid multi-factor authentication settings found for this provider.')
                );
            } else {
                return $this->redirect('/');
            }
        }
    }

    /**
     * Generate MFA verification token and cookie and decorate response accordingly
     *
     * @param string $provider name of the provider
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface $rememberMeForAMonthSetting Remember a month setting.
     * @return void
     */
    protected function _generateMFaToken(
        string $provider,
        SessionIdentificationServiceInterface $sessionIdentificationService,
        RememberAMonthSettingInterface $rememberMeForAMonthSetting
    ) {
        $uac = $this->User->getAccessControl();
        $sessionId = $sessionIdentificationService->getSessionIdentifier($this->getRequest());
        $token = MfaVerifiedToken::get($uac, $provider, $sessionId, (bool)$this->request->getData('remember'));

        /**
         * Set expiry to null(Session) by default, i.e. setting is disabled.
         * Only check for remember request data field if "remember me for a month" setting is enabled.
         */
        $expiryAt = null;
        if ($rememberMeForAMonthSetting->isEnabled()) {
            $expiryAt = $this->request->getData('remember') ?
                (new FrozenDate())->addDays(MfaVerifiedCookie::MAX_DURATION_IN_DAYS) :
                null;
        }

        $cookie = MfaVerifiedCookie::get($this->getRequest(), $token, $expiryAt);
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
            $redirect = $this->SanitizeUrl->sanitizeRedirect('/mfa/verify');
            $this->redirect(Router::url($redirect, true));
        }
    }
}
