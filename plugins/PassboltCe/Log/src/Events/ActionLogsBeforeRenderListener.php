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
 * @since         3.12.0
 */
namespace Passbolt\Log\Events;

use App\Utility\UserAction;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Passbolt\Log\Service\ActionLogs\ActionLogsCreateService;

class ActionLogsBeforeRenderListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.beforeRender' => 'logControllerAction',
        ];
    }

    /**
     * Log controller action.
     *
     * @param \Cake\Event\Event $event the event
     * @return void
     */
    public function logControllerAction(Event $event)
    {
        try {
            /** @var \Cake\Controller\Controller $controller */
            $controller = $event->getSubject();
            $userAction = UserAction::getInstance();
            (new ActionLogsCreateService())->create($userAction, $controller);
        } catch (\PDOException | MissingConnectionException $exception) {
            // Fail gracefully if database connection is not available.
            // Useful if we are already rendering an error page related to PDOException
            Log::error('Could not connect to Database.');
        }
    }
}
