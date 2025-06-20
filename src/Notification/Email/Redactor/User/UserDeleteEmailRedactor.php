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
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersDeleteController;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Passbolt\Locale\Service\LocaleService;

/**
 * Class UserDeleteEmailRedactor
 */
class UserDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.group.user.delete';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $this->Users = $usersTable;
        $emailCollection = new EmailCollection();

        $user = $event->getData('user');
        $groupsIds = $event->getData('groupsIds');
        $deletedById = $event->getData('deletedBy');

        if (empty($groupsIds)) {
            return $emailCollection;
        }

        $deletedBy = $this->Users->findFirstForEmail($deletedById);
        /** @var array<\App\Model\Entity\User> $recipients */
        $recipients = $this->getRecipientsWithGroups($groupsIds);

        foreach ($recipients as $recipient) {
            $groups = [];
            foreach ($recipient->groups_users as $gu) {
                if (isset($gu->group)) {
                    $groups[] = $gu->group;
                }
            }
            $email = $this->createDeleteUserEmail($recipient, $user, $groups, $deletedBy);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param array $groupsIds Groups IDs
     * @return \Cake\ORM\Query\SelectQuery
     */
    private function getRecipientsWithGroups(array $groupsIds): SelectQuery
    {
        $filter = [
            'Groups.id IN' => $groupsIds,
            'GroupsUsers.is_admin' => 1,
        ];

        // This is ugly CakePHP https://github.com/cakephp/cakephp/issues/15689
        return $this->Users->find('locale')
            ->find('notDisabled')
            ->groupBy($this->Users->aliasField('id'))
            ->select($this->Users)
            ->contain('GroupsUsers.Groups', function (Query $q) use ($filter) {
                return $q->where($filter);
            })
            ->innerJoinWith('GroupsUsers.Groups', function (Query $q) use ($filter) {
                return $q->where($filter);
            });
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient
     * @param \App\Model\Entity\User $user User
     * @param array<\App\Model\Entity\Group> $groups Groups
     * @param \App\Model\Entity\User $deletedBy User admin who deleted the user
     * @return \App\Notification\Email\Email
     */
    private function createDeleteUserEmail(User $recipient, User $user, array $groups, User $deletedBy): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($deletedBy, $user) {
                return __(
                    '{0} deleted user {1}',
                    $deletedBy->profile->first_name,
                    $user->profile->first_name
                );
            }
        );

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
    public function getSubscribedEvents(): array
    {
        return [
            UsersDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
    }
}
