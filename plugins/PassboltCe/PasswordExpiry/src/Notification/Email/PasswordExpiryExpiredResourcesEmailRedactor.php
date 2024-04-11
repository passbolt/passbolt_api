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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Notification\Email;

use App\Model\Entity\Permission;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;

class PasswordExpiryExpiredResourcesEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/PasswordExpiry.LU/expired_password';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            PasswordExpiryExpireResourcesService::PASSWORD_EXPIRY_RESOURCES_EXPIRED_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.expire';
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        /** @var string[] $resourceIds */
        $resourceIds = $event->getData('resourceIds');
        /** @var string[] $userIdsToSkip */
        $userIdsToSkip = $event->getData('userIdsToSkip') ?? [];
        $usersToNotify = $this->findUsersToNotify($resourceIds, $userIdsToSkip);

        // Send emails to all the users
        foreach ($usersToNotify as $user) {
            $emailCollection->addEmail($this->createEmail($user));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user The recipient to send email to.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $user): Email
    {
        $subject = (new LocaleService())->translateString(
            $user->locale,
            function () {
                return __('Some of your passwords expired');
            }
        );

        return new Email(
            $user,
            $subject,
            [
                'body' => compact('user', 'subject'),
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * Find all users that have owner access to the resources that are being expired, in order
     * to notify them
     *
     * @param array $expiringResourcesIds Resources that have just been expired
     * @param array $userIdsToSkip Users that should not be notified, e.g. if a user is being disabled or deleted
     * @return \Cake\ORM\Query
     */
    protected function findUsersToNotify(array $expiringResourcesIds, array $userIdsToSkip): Query
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $usersToNotify = $UsersTable
            ->find('notDisabled')
            ->find('active')
            ->find('locale')
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->order([], true); // Remove any order as it is not relevant here and breaks in MySQL
        if (!empty($userIdsToSkip)) {
            $usersToNotify->whereNotInList('Users.id', $userIdsToSkip);
        }

        return $UsersTable->filterQueryByResourcesAccess($usersToNotify, $expiringResourcesIds, [Permission::OWNER]);
    }
}
