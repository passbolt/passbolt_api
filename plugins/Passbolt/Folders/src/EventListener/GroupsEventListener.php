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
 * @since         2.13.0
 */

namespace Passbolt\Folders\EventListener;

use App\Service\Groups\GroupsUpdateService;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Passbolt\Folders\Service\Groups\GroupsAfterUserAddedService;
use Passbolt\Folders\Service\Groups\GroupsAfterUserRemovedService;

/**
 * Listen when an event happens on a group:
 * - A user is added to a group.
 * - A user is removed from a group.
 *
 * @package Passbolt\Folders\EventListener
 */
class GroupsEventListener implements EventListenerInterface
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            GroupsUpdateService::AFTER_GROUP_USER_REMOVED_EVENT_NAME => 'handleGroupsAfterUserRemovedEvent',
            GroupsUpdateService::AFTER_GROUP_USER_ADDED_EVENT_NAME => 'handleGroupsAfterUserAddedEvent',
        ];
    }

    /**
     * Handle a group after user added event.
     *
     * @param Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleGroupsAfterUserAddedEvent(Event $event)
    {
        $uac = $event->getData('accessControl');
        $groupUser = $event->getData('groupUser');
        $service = new GroupsAfterUserAddedService();
        $service->afterUserAdded($uac, $groupUser);
    }

    /**
     * Handle a group after user removed event.
     *
     * @param Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleGroupsAfterUserRemovedEvent(Event $event)
    {
        $groupUser = $event->getData('groupUser');
        $service = new GroupsAfterUserRemovedService();
        $service->afterUserRemoved($groupUser);
    }
}
