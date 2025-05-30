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

namespace App\Notification\Email\Redactor\Share;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Resources\ResourcesShareService;
use Cake\Event\Event;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;

class ShareEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/resource_share';
    public const TEMPLATE_V5 = 'Passbolt/Metadata.LU/resource_share_v5';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private UsersTable $usersTable;

    /**
     * @param array|null $config Configuration for redactor
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table
     */
    public function __construct(?array $config = [], ?UsersTable $usersTable = null)
    {
        $this->setConfig($config);
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            ResourcesShareService::SHARE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.share';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $resource = $event->getData('resource');
        $secrets = $event->getData('secrets') ?? [];
        $ownerId = $event->getData('ownerId');

        // for now only handle the new share
        // e.g. we don't notify when permission changes or are removed
        $userIds = Hash::extract($secrets, '{n}.user_id');
        if (!empty($userIds)) {
            // Get the details of whoever did the changes
            $owner = $this->usersTable->findFirstForEmail($ownerId);
            $users = $this->getUserFromIds($userIds)->all()->toArray();

            if (empty($users)) {
                return $emailCollection;
            }
            $secrets = Hash::combine($secrets, '{n}.user_id', '{n}.data');

            foreach ($users as $user) {
                $emailCollection->addEmail(
                    $this->createShareEmail($user, $owner, $resource, $secrets[$user->id])
                );
            }
        }

        return $emailCollection;
    }

    /**
     * Return a collection of users from a list of user ids
     *
     * @param array $userIds A list of user ids
     * @return \Cake\ORM\Query\SelectQuery
     */
    private function getUserFromIds(array $userIds): SelectQuery
    {
        return $this->usersTable
            ->find('locale')
            ->find('notDisabled')
            ->where(['Users.id IN' => $userIds]);
    }

    /**
     * @param \App\Model\Entity\User $recipient User to send email to
     * @param \App\Model\Entity\User $owner Owner
     * @param \App\Model\Entity\Resource $resource Resource
     * @param string   $secret Secret
     * @return \App\Notification\Email\Email
     */
    private function createShareEmail(User $recipient, User $owner, Resource $resource, string $secret): Email
    {
        $resourceDto = MetadataResourceDto::fromArray($resource->toArray());
        $isV5 = $resourceDto->isV5();

        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($owner, $resource, $isV5) {
                $subject = __('{0} shared the resource {1}', $owner->profile->first_name, $resource->name);
                if ($isV5) {
                    $subject = __('{0} shared a resource', $owner->profile->first_name);
                }

                return $subject;
            }
        );

        $data = [
            'body' => [
                'owner' => $owner,
                'resource' => $resource,
                'secret' => $secret,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
                'showSecret' => $this->getConfig('show.secret'),
                'isV5' => $isV5,
            ],
            'title' => $subject,
        ];

        $template = $isV5 ? self::TEMPLATE_V5 : self::TEMPLATE;

        return new Email($recipient, $subject, $data, $template);
    }
}
