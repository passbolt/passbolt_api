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
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * Class AddMfaCookieOnSuccessfulJwtLogin
 *
 * @package Passbolt\MultiFactorAuthentication\Event
 *
 * On JWT login, it is possible that the request
 * contains a valid MFA token. If so, and if the login was successful,
 * the MFA token will be provided in the response.
 *
 * Since the response is not accessible from the authenticator,
 * the present event has to be split in two methods
 * 1. detecting
 * that
 */
class AddMfaCookieOnSuccessfulJwtLogin implements EventListenerInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private $onSuccessfulJwtLogin = false;

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY => 'isJwtLoginSuccessful',
            'Controller.initialize' => 'addMfaTokenOnSuccessfulJwtLogin',
        ];
    }

    /**
     * Detects that the current action is a JWT login, and
     * this login was successful.
     *
     * @return void
     */
    public function isJwtLoginSuccessful(): void
    {
        $this->onSuccessfulJwtLogin = true;
    }

    /**
     * If the login was successful and a valid MFA token is found in the request
     * set the MFA cookie in the response.
     *
     * @param \Cake\Event\EventInterface $event Initialize controller event
     * @return void
     */
    public function addMfaTokenOnSuccessfulJwtLogin(EventInterface $event): void
    {
        if ($this->onSuccessfulJwtLogin !== true) {
            return;
        }

        /** @var \App\Controller\AppController $controller */
        $controller = $event->getSubject();
        $request = $controller->getRequest();

        $mfaCheckNotRequired = $request->getAttribute(
            MfaRequiredCheckMiddleware::IS_MFA_CHECK_NOT_REQUIRED_ATTRIBUTE
        );

        if (
            $mfaCheckNotRequired &&
            $controller->getRequest()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS)
        ) {
            $mfaCookie = $controller->getRequest()->getCookieCollection()->get(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
            $controller->setResponse(
                $controller->getResponse()->withCookie($mfaCookie)
            );
        }
    }
}
