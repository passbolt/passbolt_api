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
 * @since         3.11.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Service\Cookie\AbstractSecureCookieService;
use Cake\Http\Exception\ServiceUnavailableException;
use Cake\Http\Response;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStartDuoAuthenticationService;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStateCookieService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * @property \App\Controller\Component\SanitizeUrlComponent $SanitizeUrl
 */
class DuoSetupPromptPostController extends MfaSetupController
{
    /**
     * @return void
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('SanitizeUrl');
    }

    /**
     * Handle Duo setup prompt POST request.
     *
     * @param \Duo\DuoUniversal\Client|null $duoSdkClient Duo SDK Client
     * @return \Cake\Http\Response|null
     */
    public function post(?Client $duoSdkClient = null): ?Response
    {
        $this->_assertRequestNotJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_DUO);
        $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_DUO);

        $redirect = $this->SanitizeUrl->sanitizeRedirect('/mfa/setup', true);
        $startAuthService = new MfaDuoStartDuoAuthenticationService(
            AuthenticationToken::TYPE_MFA_SETUP,
            $duoSdkClient
        );
        try {
            $duoAuthenticationRequest = $startAuthService->start(
                $this->User->getAccessControl(),
                $redirect
            );
        } catch (ServiceUnavailableException $e) {
            $this->Flash->error($e->getMessage());

            return $this->redirect($redirect);
        }
        $cookie = (new MfaDuoStateCookieService())->createDuoStateCookie(
            $duoAuthenticationRequest->authenticationToken->token,
            AbstractSecureCookieService::isSslOrCookiesSecure($this->getRequest())
        );

        $this->setResponse($this->getResponse()->withCookie($cookie));

        return $this->redirect($duoAuthenticationRequest->duoAuthenticationUrl);
    }
}
