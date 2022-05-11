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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Event;

use App\Middleware\ContainerAwareMiddlewareTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * Class AddMfaCookieOnSuccessfulJwtLogin
 *
 * @package Passbolt\MultiFactorAuthentication\Event
 *
 * On JWT login, it is possible that the request
 * contains a valid MFA token. If so, and if the login was successful,
 * the MFA token will be provided in the response. Same applies to the creation
 * of a new refresh token.
 *
 * Since a refresh token is created on both successful login and successful refresh token creation
 * we here listen to the creation of refresh tokens.
 *
 * Because the response is not accessible from the authenticator,
 * the present event has to be split in two methods
 * 1. Detecting the creation of a refresh token
 * 2. At controller initialization, set the MFA cookie if valid
 * or an expired one if not valid.
 */
class AddMfaCookieOnSuccessfulRefreshTokenCreation implements EventListenerInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private $onSuccessfulRefreshTokenCreated = false;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            RefreshTokenCreateService::REFRESH_TOKEN_CREATED_EVENT => 'isRefreshTokenCreated',
            'Controller.beforeRedirect' => 'addMfaTokenOnSuccessfulRefreshTokenCreation',
            'Controller.beforeRender' => 'addMfaTokenOnSuccessfulRefreshTokenCreation',
        ];
    }

    /**
     * Detects that the current action is a JWT login, and
     * this login was successful.
     *
     * @return void
     */
    public function isRefreshTokenCreated(): void
    {
        $this->onSuccessfulRefreshTokenCreated = true;
    }

    /**
     * If a refresh token was successfully created and a valid MFA token is found in the request
     * set the MFA cookie in the response.
     *
     * @param \Cake\Event\EventInterface $event Initialize controller event
     * @return void
     */
    public function addMfaTokenOnSuccessfulRefreshTokenCreation(EventInterface $event): void
    {
        if ($this->onSuccessfulRefreshTokenCreated !== true) {
            return;
        }

        /** @var \App\Controller\AppController $controller */
        $controller = $event->getSubject();
        $request = $controller->getRequest();

        $isMfaTokenValid = $request->getAttribute(
            MfaRequiredCheckMiddleware::IS_MFA_TOKEN_VALID_ATTRIBUTE
        );

        if (
            $isMfaTokenValid &&
            $controller->getRequest()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS)
        ) {
            $mfaCookie = $controller->getRequest()->getCookieCollection()->get(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
            $newMfaCookie = MfaVerifiedCookie::get($controller->getRequest(), (string)$mfaCookie->getValue());

            $controller->setResponse(
                $controller->getResponse()->withCookie($newMfaCookie)
            );
        }
    }
}
