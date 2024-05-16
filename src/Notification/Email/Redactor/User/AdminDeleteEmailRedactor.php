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
 * @since         4.4.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersDeleteController;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use InvalidArgumentException;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Passbolt\Locale\Service\LocaleService;

/**
 * Email sent to all administrators when any administrator is deleted.
 */
class AdminDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const CONFIG_KEY_EMAIL_ENABLED = 'passbolt.email.send.admin.user.delete.admin';
    public const CONFIG_KEY_SEND_USER_EMAIL = 'passbolt.email.send.admin.user.delete.user';

    public const TEMPLATE = 'AD/admin_deleted';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $Users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var string $deletedById */
        $deletedById = $event->getData('deletedBy');
        if (!is_string($deletedById)) {
            throw new InvalidArgumentException('`deletedBy` is missing from event data.');
        }

        /** @var \App\Model\Entity\User $deletedUser */
        $deletedUser = $event->getData('user');
        if (!$deletedUser instanceof User) {
            throw new InvalidArgumentException('`user` is missing from event data.');
        }
        if (!$deletedUser->role->isAdmin()) {
            return $emailCollection;
        }

        /** @var array $groupsIds */
        $groupsIds = $event->getData('groupsIds');
        if (!is_array($groupsIds)) {
            throw new InvalidArgumentException('`groupsIds` is missing from event data.');
        }

        $deletedBy = $this->Users->findFirstForEmail($deletedById);
        $recipients = $this->getAdministrators($deletedUser, $groupsIds);

        if (Configure::read(self::CONFIG_KEY_SEND_USER_EMAIL)) {
            $recipients[] = $this->Users
                ->find('locale')
                ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
                ->where([
                    'Users.id' => $deletedUser->id,
                    'Users.deleted' => true,
                ])
                ->firstOrFail();
        }

        foreach ($recipients as $recipient) {
            $emailCollection->addEmail(
                $this->createEmail($recipient, $deletedUser, $deletedBy)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient.
     * @param \App\Model\Entity\User $user User who got deleted.
     * @param \App\Model\Entity\User $deletedBy User admin who deleted the user.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $user, User $deletedBy): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($user, $recipient, $deletedBy) {
                $operatorFullName = Purifier::clean($deletedBy->profile->full_name);
                $userFullName = Purifier::clean($user->profile->full_name);

                if ($recipient->id === $deletedBy->id) {
                    return __('You deleted administrator {0}', $userFullName);
                } elseif ($recipient->id === $user->id) {
                    return __('{0} deleted your account', $operatorFullName);
                }

                return __('{0} deleted administrator {1}', $operatorFullName, $userFullName);
            }
        );

        // Expose full name virtual field so it can be used in template file
        $user->profile->setVirtual(['full_name']);
        $deletedBy->profile->setVirtual(['full_name']);

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'user' => $user,
                    'operator' => $deletedBy,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * @param \App\Model\Entity\User $deletedUser User who got deleted.
     * @param array $groupsIds Group IDs this user belonged to.
     * @return array
     */
    private function getAdministrators(User $deletedUser, array $groupsIds): array
    {
        $adminsQuery = $this->Users
            ->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->where(['Users.id !=' => $deletedUser->id]);

        // Filter out group managers to prevent sending duplicate email
        $groupUserDeleteRedactorEnabled = EmailNotificationSettings::get('send.group.user.delete');
        if (!empty($groupsIds) && $groupUserDeleteRedactorEnabled) {
            $groupManagerIdsSubQuery = $this->Users
                ->find('notDisabled')
                ->group($this->Users->aliasField('id'))
                ->select('id')
                ->matching('GroupsUsers', function (Query $q) use ($groupsIds) {
                    return $q->where([
                        'GroupsUsers.group_id IN' => $groupsIds,
                        'GroupsUsers.is_admin' => 1,
                    ]);
                });

            $adminsQuery->where(['Users.id NOT IN' => $groupManagerIdsSubQuery]);
        }

        return $adminsQuery->toArray();
    }
}
