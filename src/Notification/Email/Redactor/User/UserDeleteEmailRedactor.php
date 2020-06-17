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

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersDeleteController;
use App\Model\Entity\Group;
use App\Model\Entity\User;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class UserDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @param UsersTable|null       $usersTable UsersTable
     * @param GroupsUsersTable|null $groupsUsersTable GroupsUsersTable
     */
    public function __construct(UsersTable $usersTable = null, GroupsUsersTable $groupsUsersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->groupsUsersTable = $groupsUsersTable ?? TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        $user = $event->getData('user');
        $groupsIds = $event->getData('groupsIds');
        $deletedById = $event->getData('deletedBy');

        if (empty($groupsIds)) {
            return $emailCollection;
        }

        $deletedBy = $this->usersTable->findFirstForEmail($deletedById);
        $groupManagers = $this->getGroupManagers($groupsIds);

        $usersToNotify = [];
        foreach ($groupManagers as $groupManager) {
            $usersToNotify[$groupManager->user->username][] = $groupManager->group;
        }

        foreach ($usersToNotify as $username => $groups) {
            $emailCollection->addEmail($this->createDeleteUserEmail($username, $user, $groups, $deletedBy));
        }

        return $emailCollection;
    }

    /**
     * @param array $groupsIds Groups IDs
     * @return ResultSetInterface
     */
    private function getGroupManagers(array $groupsIds)
    {
        return $this->groupsUsersTable->find()
            ->select()
            ->contain(['Users', 'Groups'])
            ->where(['group_id IN' => $groupsIds, 'is_admin' => 1])
            ->all();
    }

    /**
     * @param string  $recipient Email recipient
     * @param User    $user User
     * @param Group[] $groups Groups
     * @param User    $deletedBy User admin who deleted the user
     * @return Email
     */
    private function createDeleteUserEmail(string $recipient, User $user, array $groups, User $deletedBy)
    {
        $subject = __('{0} deleted user {1}', $deletedBy->profile->first_name, $user->profile->first_name);

        return new Email(
            $recipient,
            $subject,
            ['body' => ['user' => $user, 'groups' => $groups, 'admin' => $deletedBy], 'title' => $subject],
            'GM/user_delete'
        );
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            UsersDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
    }
}
