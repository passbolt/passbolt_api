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

class AppendProvidersToJwtChallenge implements EventListenerInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            GpgJwtAuthenticator::MAKE_ARMORED_CHALLENGE_EVENT_NAME => 'appendProvidersToJwtChallenge',
        ];
    }

    /**
     * On login with JWT, if the MFA authentication is required but missing,
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

        $isMfaAuthenticationRequired = (new IsMfaAuthenticationRequiredService())->isMfaCheckRequired(
            $request,
            $mfaSettings,
            $uac,
            $this->getContainer($request)->get(SessionIdentificationServiceInterface::class)
        );

        if ($isMfaAuthenticationRequired) {
            $challenge['providers'] = $mfaSettings->getEnabledProviders();
            $event->setData($challenge);
        }
    }
}
