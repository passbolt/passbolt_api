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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Controller\Google;

use App\Service\Cookie\AbstractSecureCookieService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\Google\SsoGoogleService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Service\SsoStates\SsoStatesGetService;
use Passbolt\SsoRecover\Service\SsoRecoverAssertService;

class SsoGoogleStage2Controller extends AbstractSsoController
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['triage']);
    }

    /**
     * Handle both user is admin and trying to validate a setting or regular user SSO return.
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @return void
     */
    public function triage(AbstractSecureCookieService $cookieService): void
    {
        // Get state from cookie and URL to prevent CSRF
        $state = $this->getStateFromUrlAndCookie();

        // Handle any error code
        $this->assertErrorFromUrlQuery();

        // Check that there is a code in the URL query
        $code = $this->getCodeFromUrlQuery();

        try {
            $ssoState = (new SsoStatesGetService())->getOrFail($state);
        } catch (\Exception $e) {
            // Remap status code 404 with 400
            throw new BadRequestException($e->getMessage(), 400, $e);
        }

        if ($this->User->isAdmin()) {
            $this->stage2AsAdmin($cookieService, $ssoState, $code);
        } else {
            $this->stage2($cookieService, $ssoState, $code);
        }
    }

    /**
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state.
     * @param string $code jwt
     * @return void
     */
    public function stage2AsAdmin(AbstractSecureCookieService $cookieService, SsoState $ssoState, string $code): void
    {
        try {
            // Get the draft settings
            $settingsDto = (new SsoSettingsGetService())->getDraftByIdOrFail($ssoState->sso_settings_id, true);
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        $service = new SsoGoogleService($cookieService, $settingsDto);
        $uac = $service->assertStateCodeAndGetUac($ssoState, $code, $this->User->ip(), $this->User->userAgent());

        // Create authentication token for next step, e.g. activate settings
        $ssoAuthToken = $service->createAuthTokenToActiveSettings($uac, $service->getSettings()->id);

        $this->response = $this->getResponse()->withCookie($service->clearStateCookie());
        $this->redirect(Router::url("/sso/login/dry-run/success?token={$ssoAuthToken->token}", true));
    }

    /**
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state.
     * @param string $code jwt
     * @return void
     */
    public function stage2(AbstractSecureCookieService $cookieService, SsoState $ssoState, string $code): void
    {
        $this->User->assertNotLoggedIn();

        try {
            // Get the settings associated with the state
            $settingsDto = (new SsoSettingsGetService())->getByIdOrFail($ssoState->sso_settings_id);
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        $service = new SsoGoogleService($cookieService, $settingsDto);

        switch ($ssoState->type) {
            case SsoState::TYPE_SSO_GET_KEY:
                $uac = $service->assertStateCodeAndGetUac(
                    $ssoState,
                    $code,
                    $this->User->ip(),
                    $this->User->userAgent()
                );
                // Create SSO auth token for next step, e.g. get keys
                $ssoAuthToken = $service->createAuthTokenToGetKey($uac, $service->getSettings()->id);
                $successUrl = Router::url("/sso/login/success?token={$ssoAuthToken->token}", true);
                break;
            case SsoState::TYPE_SSO_RECOVER:
                if (!$this->isFeaturePluginEnabled('SsoRecover')) {
                    throw new BadRequestException(__('SsoRecover plugin is disabled.'));
                }

                $ssoRecoverAssertService = new SsoRecoverAssertService();

                $successUrl = $ssoRecoverAssertService->assertAndGetRedirectUrl(
                    $service,
                    $ssoState,
                    $code,
                    $this->User->ip(),
                    $this->User->userAgent(),
                    SsoSetting::PROVIDER_GOOGLE
                );
                break;
            default:
                throw new BadRequestException(__('The SSO state type is invalid.'));
        }

        $this->response = $this->getResponse()->withCookie($service->clearStateCookie());
        $this->redirect($successUrl);
    }
}
