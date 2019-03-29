<?php
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
 */
namespace Passbolt\Log\Events\Traits;

use App\Utility\UserAction;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

trait ControllerActionTrait
{

    /**
     * Log controller action.
     * @param Event $event the event
     * @return void
     */
    public function logControllerAction(Event $event)
    {
        $statusCode = $event->getSubject()->response->getStatusCode();
        $status = (int)($statusCode === 200);
        $userAction = UserAction::getInstance();
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $ActionLogs->create($userAction, $status);
    }
}
