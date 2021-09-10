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
namespace Passbolt\MultiFactorAuthentication\Middleware;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Utility\UserAccessControl;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AppendProvidersToJwtChallengeMiddleware implements MiddlewareInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * Middleware listening to the creation of an armored challenge
     * on successful JWT Authentication.
     *
     * If the user in successfully authenticated, but MFA authentication
     * is incomplete (MFA cookie missing or invalid)
     * appends the list of the enabled providers to the armored challenge returned.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     * @see GpgJwtAuthenticator::makeArmoredChallenge()
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        EventManager::instance()->on(
            GpgJwtAuthenticator::MAKE_ARMORED_CHALLENGE_EVENT_NAME,
            function (EventInterface $event) use ($request) {
                /** @var \Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator $authenticator */
                $authenticator = $event->getSubject();
                $user = $authenticator->getUser();
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
        );

        return $handler->handle($request);
    }
}
