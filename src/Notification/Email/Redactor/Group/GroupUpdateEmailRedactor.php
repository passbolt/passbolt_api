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
 * @since         2.14.0
 */

namespace App\Notification\Email\Redactor\Group;

use App\Controller\Groups\GroupsUpdateController;
use App\Model\Entity\Group;
use App\Model\Table\UsersTable;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;

class GroupUpdateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use EventDispatcherTrait;
    use SubscribedEmailRedactorTrait;

    const CREATE_EVENT_NAME = 'GroupsUpdateEmailRedactor.create';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param EventManager|null $eventManager Event Manager Instance
     * @param UsersTable|null   $usersTable Users Table
     */
    public function __construct(EventManager $eventManager = null, UsersTable $usersTable = null)
    {
        if ($eventManager !== null) {
            $this->setEventManager($eventManager);
        }
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            GroupsUpdateController::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        /** @var Group $resource */
        $group = $event->getData('group');
        $addedGroupsUsers = $event->getData('addedGroupsUsers');
        $updatedGroupsUsers = $event->getData('updatedGroupsUsers');
        $removedGroupsUsers = $event->getData('removedGroupsUsers');
        $userId = $event->getData('userId');

        // Re-dispatch update email event data to collaborating redactors
        $this->dispatchEvent(static::CREATE_EVENT_NAME, [
            'group' => $group,
            'addedGroupsUsers' => $addedGroupsUsers,
            'updatedGroupsUsers' => $updatedGroupsUsers,
            'removedGroupsUsers' => $removedGroupsUsers,
            'userId' => $userId,
        ]);

        return $emailCollection;
    }
}
