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
 * @since         4.1.0
 */

namespace Passbolt\Sso\Controller;

use App\Service\Cookie\AbstractSecureCookieService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Passbolt\Sso\Error\Exception\OAuth2Exception;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Service\SsoStates\SsoStatesGetService;
use Passbolt\SsoRecover\Service\SsoRecoverAssertService;

abstract class AbstractSso2Stage2Controller extends AbstractSsoController
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
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService cookie service
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto $settingsDto sso setting dto
     * @return \Passbolt\Sso\Service\Sso\AbstractSsoService SsoOAuth2Service
     */
    abstract protected function ssoServiceFactory(
        AbstractSecureCookieService $cookieService,
        SsoSettingsDto $settingsDto
    ): AbstractSsoService;

    /**
     * @return string provider name
     */
    abstract protected function getProviderName(): string;

    /**
     * Handle both user is admin and trying to validate a setting or regular user SSO return
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @return void
     */
    public function triage(AbstractSecureCookieService $cookieService): void
    {
        if ($this->getRequest()->is('get')) {
            // Get state from cookie and URL to prevent CSRF
            $state = $this->getStateFromUrlAndCookie();

            // Handle any error code
            $this->assertErrorFromUrlQuery();

            // Check that there is a code in the URL query
            $code = $this->getCodeFromUrlQuery();
        } else {
            /**
             * Handle POST
             */
            // Get state from request data and assert against cookie value to prevent CSRF
            $state = $this->getStateAndAssertAgainstCookie();
            // Handle any error code
            $this->assertErrorFromRequestData();
            // Check that there is a code in the request data
            $code = $this->getCodeFromRequestData();
        }

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
    protected function stage2(AbstractSecureCookieService $cookieService, SsoState $ssoState, string $code): void
    {
        $this->User->assertNotLoggedIn();

        try {
            // Get the settings associated with the state
            $settingsDto = (new SsoSettingsGetService())->getByIdOrFail($ssoState->sso_settings_id);
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        $service = $this->ssoServiceFactory($cookieService, $settingsDto);

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
                    $this->getProviderName()
                );
                break;
            default:
                throw new BadRequestException(__('The SSO state type is invalid.'));
        }

        $this->response = $this->getResponse()->withCookie($service->clearStateCookie());
        $this->redirect($successUrl);
    }

    /**
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state.
     * @param string $code jwt
     * @return void
     */
    protected function stage2AsAdmin(AbstractSecureCookieService $cookieService, SsoState $ssoState, string $code): void
    {
        try {
            // Get the draft settings
            $settingsDto = (new SsoSettingsGetService())->getDraftByIdOrFail($ssoState->sso_settings_id, true);
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        try {
            $service = $this->ssoServiceFactory($cookieService, $settingsDto);
            $uac = $service->assertStateCodeAndGetUac($ssoState, $code, $this->User->ip(), $this->User->userAgent());
        } catch (OAuth2Exception $e) { // Remap 500 error with 400 when admin is setting up SSO
            throw new BadRequestException($e->getMessage(), 400, $e);
        }

        // Create authentication token for next step, e.g. activate settings
        $ssoAuthToken = $service->createAuthTokenToActiveSettings($uac, $service->getSettings()->id);

        $this->response = $this->getResponse()->withCookie($service->clearStateCookie());
        $this->redirect(Router::url("/sso/login/dry-run/success?token={$ssoAuthToken->token}", true));
    }
}
