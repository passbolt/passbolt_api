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
use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\PasswordExpiry\Event\PasswordExpiryResourceMarkedAsExpiredEventListener;

class PasswordExpiryPasswordMarkedExpiredEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/PasswordExpiry.LU/password_marked_expired';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            PasswordExpiryResourceMarkedAsExpiredEventListener::EVENT_RESOURCE_MARKED_AS_EXPIRED,
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
        /** @var Resource $resource */
        $resource = $event->getData('resource');
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var \App\Model\Entity\User $operator */
        $operator = $UsersTable->find()
            ->where(['Users.id' => $resource->modified_by])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])->firstOrFail();
        $usersToNotify = $this->findOwnersToNotify($resource->id, $operator);

        // Send emails to all the users
        foreach ($usersToNotify as $user) {
            $emailCollection->addEmail($this->createEmail($user, $operator, $resource));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user The recipient to send email to.
     * @param \App\Model\Entity\User $operator User performing the action.
     * @param \App\Model\Entity\Resource $resource Resource marked as expired.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $user, User $operator, Resource $resource): Email
    {
        $resourceName = Purifier::clean($resource->name);
        $operatorFullName = Purifier::clean($operator->profile->full_name);
        $resourceId = $resource->id;
        $subject = (new LocaleService())->translateString(
            $user->locale,
            function () use ($resourceName, $operatorFullName) {
                return __(
                    '{0} marked the password {1} as expired',
                    $operatorFullName,
                    $resourceName
                );
            }
        );

        return new Email(
            $user,
            $subject,
            [
                'body' => compact('user', 'subject', 'operator', 'resourceId'),
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * Find all users that have access to the resource that is being expired, in order
     * to notify them
     *
     * @param string $expiringResourceId Resources that have just been expired
     * @param \App\Model\Entity\User $operator User performing the action.
     * @return \Cake\ORM\Query
     */
    protected function findOwnersToNotify(string $expiringResourceId, User $operator): Query
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $usersToNotify = $UsersTable
            ->find('notDisabled')
            ->find('active')
            ->find('locale')
            ->where(['Users.id !=' => $operator->id])
            ->order([], true); // Remove any order as it is not relevant here and breaks in MySQL

        return $UsersTable->filterQueryByResourcesAccess($usersToNotify, [$expiringResourceId], [Permission::OWNER]);
    }
}
