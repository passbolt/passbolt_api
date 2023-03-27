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
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Engine\BaseLog;
use Cake\Log\Log;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Service\ActionLogs\ActionLogsCreateService;
use Passbolt\Log\Strategy\ActionLogsAbstractQueryStrategy;
use Passbolt\Log\Strategy\ActionLogsDefaultQueryStrategy;
use Psr\Log\LogLevel;

class ActionLogsAfterCreateListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ActionLogsCreateService::MODEL_ACTION_LOGS_AFTER_SAVE => 'saveEntityOnLogEngines',
        ];
    }

    /**
     * Logs after saving a log on all log engines
     *
     * @param \Cake\Event\Event $event User register event
     * @return void
     */
    public function saveEntityOnLogEngines(EventInterface $event): void
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        /** @var \Passbolt\Log\Model\Entity\ActionLog $actionLog */
        $actionLog = $event->getData('actionLog');
        foreach (Log::configured() as $engineAlias) {
            /** @var \Cake\Log\Engine\BaseLog $engine */
            $engine = Log::engine($engineAlias);
            if ($this->isLogEngineAuditLogAndActive($engine)) {
                $this->log($controller, $engine, $actionLog);
            }
        }
    }

    /**
     * @param \Cake\Controller\Controller $controller App Controller
     * @param \Cake\Log\Engine\BaseLog $engine Log Engine
     * @param \Passbolt\Log\Model\Entity\ActionLog $actionLog Action Log
     * @return void
     */
    protected function log(Controller $controller, BaseLog $engine, ActionLog $actionLog): void
    {
        $msg = $this->getStrategy($engine, $controller)->query(
            $actionLog,
        );
        if ($msg === false) {
            return;
        }

        $level = $actionLog->isStatusSuccess() ? LogLevel::INFO : LogLevel::ERROR;
        $engine->log(
            $level,
            $msg,
            ['actionLogs']
        );
    }

    /**
     * Read in the engine configuration if the logging is enabled
     *
     * @param \Cake\Log\Engine\BaseLog $engine Log Engine
     * @return bool
     */
    protected function isLogEngineAuditLogAndActive(BaseLog $engine): bool
    {
        if (!in_array('actionLogs', $engine->getConfig('scopes', []))) {
            return false;
        }

        return filter_var($engine->getConfig('enabled'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param \Cake\Log\Engine\BaseLog $engine Log Engine
     * @param \Cake\Controller\Controller $controller Controller
     * @return \Passbolt\Log\Strategy\ActionLogsAbstractQueryStrategy
     * @throws \Cake\Core\Exception\CakeException if the User Action was not initialized yet
     */
    protected function getStrategy(BaseLog $engine, Controller $controller): ActionLogsAbstractQueryStrategy
    {
        $strategy = $engine->getConfig('strategy', ActionLogsDefaultQueryStrategy::class);
        try {
            $strategy = new $strategy(
                $controller->getRequest(),
                $controller->getResponse(),
                UserAction::getInstance()->getUserAccessControl()
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException(
                __('The strategy should extend the class: {0}', ActionLogsAbstractQueryStrategy::class),
                500,
                $e
            );
        }

        return $strategy;
    }
}
