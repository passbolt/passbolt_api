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
 * @since         3.4.1
 */
namespace Passbolt\JwtAuthentication\Event;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;
use Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService;

class LogAuthenticationWithNonValidJwtAccessToken implements EventListenerInterface
{
    public const AUTHENTICATION_WITH_INVALID_ACCESS_TOKEN_EVENT = 'authentication_with_invalid_access_token_event';

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'logIdentificationWithNonValidJwtAccessToken',
        ];
    }

    /**
     * When a user attempts to authenticate with non-valid access token
     * Then log this in the error file.
     * An Event is triggered for alternative uses. For the moment this event
     * is used only for testing purpose. Triggering events by events is generally
     * not recommended.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function logIdentificationWithNonValidJwtAccessToken(EventInterface $event): void
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        $auth = $controller->getRequest()->getAttribute('authentication');
        if (!($auth instanceof JwtAuthenticationService)) {
            return;
        }

        $isTokenInHeader = (new JwtRequestDetectionService($controller->getRequest()))->isJwtAccessTokenSetInHeader();
        if (!$isTokenInHeader) {
            return;
        }

        if (!$auth->getResult()->isValid()) {
            $path = $controller->getRequest()->getUri()->getPath();
            // This message is dedicated to admins and is therefore not translated.
            $message = "The access token provided for '$path' is not valid.";
            $event = new Event(
                self::AUTHENTICATION_WITH_INVALID_ACCESS_TOKEN_EVENT,
                $auth,
                compact('message')
            );
            EventManager::instance()->dispatch($event);
            Log::error($message);
        }
    }
}
