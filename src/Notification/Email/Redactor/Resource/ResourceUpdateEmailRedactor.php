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

namespace App\Notification\Email\Redactor\Resource;

use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Resources\ResourcesUpdateService;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Locale\Service\LocaleService;

class ResourceUpdateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/resource_update';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param array|null $config Configuration for the redactor
     * @param \App\Model\Table\UsersTable $usersTable Users Table
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
            ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.update';
    }

    /**
     * @param \Cake\Event\Event $event Resource update event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $event->getData('resource');
        /** @var \App\Model\Entity\Secret[] $secrets */
        $secrets = $event->getData('secrets');

        // Get the users that can access this resource
        $options = ['contain' => ['role'], 'filter' => ['has-access' => [$resource->id]]];
        /** @var \App\Model\Entity\User[] $users */
        $users = $this->usersTable->findIndex(Role::USER, $options)
            ->find('locale')
            ->find('notDisabled');
        $owner = $this->usersTable->findFirstForEmail($resource->modified_by);

        $secretsDataById = [];
        // Secrets can be empty when only metadata is updated
        if (!empty($secrets)) {
            $secretsDataById = Hash::combine($secrets, '{n}.user_id', '{n}.data');
        }

        // Send emails to everybody that can see the resource
        foreach ($users as $user) {
            $emailCollection->addEmail(
                $this->createUpdateEmail($user, $owner, $resource, $secretsDataById[$user->id] ?? null)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Email of the recipient user
     * @param \App\Model\Entity\User $owner User who executed the action
     * @param \App\Model\Entity\Resource $resource Resource
     * @param string|null $armoredSecret The secret data string if present
     * @return \App\Notification\Email\Email
     */
    private function createUpdateEmail(User $recipient, User $owner, Resource $resource, ?string $armoredSecret): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($recipient, $owner, $resource) {
                $resourceName = Purifier::clean($resource->name);
                if ($recipient->id === $owner->id) {
                    return __('You edited the password {0}', $resourceName);
                }

                return __(
                    '{0} edited the password {1}',
                    Purifier::clean($owner->profile->first_name),
                    $resourceName
                );
            }
        );

        $data = [
            'body' => [
                'user' => $owner,
                'resource' => $resource,
                'armoredSecret' => $armoredSecret,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
                'showSecret' => $this->getConfig('show.secret'),
            ], 'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
