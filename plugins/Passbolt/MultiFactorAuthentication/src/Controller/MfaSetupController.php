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
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

abstract class MfaSetupController extends MfaController
{
    /**
     * Fail is account is already setup for this authentication provider
     *
     * @param string $provider name of the provider
     * @throws \Cake\Http\Exception\BadRequestException
     * @return bool
     */
    protected function _notAlreadySetupOrFail(string $provider)
    {
        if ($this->mfaSettings->getAccountSettings() !== null) {
            $isReadyToUse = $this->mfaSettings
                ->getAccountSettings()
                ->isProviderReady($provider);
            if ($isReadyToUse) {
                $msg = __('This authentication provider is already setup. Disable it first');
                throw new BadRequestException($msg);
            }
        }

        return true;
    }

    /**
     * Handle get request when ready to use settings are present
     *
     * @param string $provider name of the provider
     * @return void
     */
    protected function _handleGetExistingSettings(string $provider)
    {
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_setup')
            ->setTemplatePath(ucfirst($provider))
            ->setTemplate('setupSuccess');

        $verified = $this->mfaSettings
            ->getAccountSettings()
            ->getVerifiedFrozenTime($provider);
        $this->success(__('Multi Factor Authentication is configured!'), ['verified' => $verified]);
    }

    /**
     * Handle post setup success
     * Create auth token and cookie verification proof
     *
     * @param string $provider name of the provider
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @return void
     */
    protected function _handlePostSuccess(
        string $provider,
        SessionIdentificationServiceInterface $sessionIdentificationService
    ) {
        $sessionId = $sessionIdentificationService->getSessionIdentifier($this->getRequest());
        $token = MfaVerifiedToken::get($this->User->getAccessControl(), $provider, $sessionId);
        $cookie = MfaVerifiedCookie::get($this->getRequest(), $token, null);
        $this->response = $this->getResponse()->withCookie($cookie);

        if (!$this->request->is('json')) {
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath(ucfirst($provider))
                ->setTemplate('setupSuccess');
        }

        $verified = $this->mfaSettings
            ->getAccountSettings(true)
            ->getVerifiedFrozenTime($provider);
        $msg = __('Multi Factor Authentication is configured!');
        $this->success($msg, ['verified' => $verified]);
    }
}
