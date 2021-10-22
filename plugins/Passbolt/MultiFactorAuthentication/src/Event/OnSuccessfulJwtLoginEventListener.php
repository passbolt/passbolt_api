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

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Utility\UserAccessControl;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class OnSuccessfulJwtLoginEventListener implements EventListenerInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private $loginSuccessfulAndMfaTokenValid;

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY => 'appendProvidersToJwtChallenge',
            'Controller.initialize' => 'addMfaTokenOnSuccessfulLogin',
        ];
    }

    /**
     * On login with JWT, if the MFA authentication token is required but missing,
     * appends the list of MFA providers within the challenge.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function appendProvidersToJwtChallenge(EventInterface $event): void
    {
        /** @var \Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator $authenticator */
        $authenticator = $event->getSubject();
        $user = $authenticator->getUser();
        $request = $authenticator->getRequest();
        $uac = new UserAccessControl($user['role']['name'], $user['id']);
        $mfaSettings = MfaSettings::get($uac);
        /** @var \App\Authenticator\SessionIdentificationServiceInterface $sessionService */
        $sessionService = $this->getContainer($request)->get(SessionIdentificationServiceInterface::class);

        $isMfaAuthenticationRequired = (new IsMfaAuthenticationRequiredService())->isMfaCheckRequired(
            $request,
            $mfaSettings,
            $uac,
            $sessionService->getSessionId($request)
        );

        if ($isMfaAuthenticationRequired) {
            $challenge['providers'] = $mfaSettings->getEnabledProviders();
            $event->setData($challenge);
        } else {
            $this->loginSuccessfulAndMfaTokenValid = true;
        }
    }

    /**
     * If the login was successful and the MFA token is valid
     * Set the MFA cookie in the response.
     *
     * @param \Cake\Event\EventInterface $event Initialize controller event
     * @return void
     */
    public function addMfaTokenOnSuccessfulLogin(EventInterface $event): void
    {
        if ($this->loginSuccessfulAndMfaTokenValid !== true) {
            return;
        }

        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        if ($controller->getRequest()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS)) {
            $mfaCookie = $controller->getRequest()->getCookieCollection()->get(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
            $controller->setResponse(
                $controller->getResponse()->withCookie($mfaCookie)
            );
        }
    }
}
