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
namespace Passbolt\Log\Service\ActionLogs;

use App\Utility\UserAction;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ActionLogsCreateService
{
    public const MODEL_ACTION_LOGS_AFTER_SAVE = 'model_action_logs_after_save';
    public const LOG_CONFIG_BLACKLIST_CONFIG_KEY = 'passbolt.plugins.log.config.blackList';

    /**
     * Create a new action_log from a userAction.
     *
     * Will not process blacklisted actions (see config).
     *
     * @param \App\Utility\UserAction $userAction user action
     * @param \Cake\Controller\Controller $controller controller
     * @return void
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException if the action log could not be saved
     */
    public function create(UserAction $userAction, Controller $controller): void
    {
        if ($this->isActionBlackListed($userAction)) {
            return;
        }

        $statusCode = $controller->getResponse()->getStatusCode();
        $status = (int)($statusCode === 200);

        /** @var \Passbolt\Log\Model\Table\ActionLogsTable $ActionLogs */
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $actionLog = $ActionLogs->create($userAction, $status);

        $afterSaveEvent = new Event(self::MODEL_ACTION_LOGS_AFTER_SAVE, $controller, compact('actionLog'));
        $controller->getEventManager()->dispatch($afterSaveEvent);
    }

    /**
     * Check whether a given action is blacklisted from being logged.
     *
     * @param \App\Utility\UserAction $userAction user action
     * @return bool
     */
    private function isActionBlackListed(UserAction $userAction): bool
    {
        $blackList = Configure::read(self::LOG_CONFIG_BLACKLIST_CONFIG_KEY, []);

        return in_array($userAction->getActionName(), $blackList);
    }
}
