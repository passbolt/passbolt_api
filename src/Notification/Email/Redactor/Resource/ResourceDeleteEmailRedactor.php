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

use App\Controller\Resources\ResourcesDeleteController;
use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;

class ResourceDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/resource_delete';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param array|null $config Configuration for the redactor
     * @param \App\Model\Table\UsersTable|null $usersTable Users table
     */
    public function __construct(?array $config = [], ?UsersTable $usersTable = null)
    {
        $this->setConfig($config);
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.delete';
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            ResourcesDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
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
        /** @var string $deletedBy */
        $deletedBy = $event->getData('deletedBy');
        /** @var \Cake\ORM\Query $users */
        $users = $event->getData('users');

        // if there is nobody, give it up. The deleter has already been removed from $users.
        if ($users->count() < 1) {
            return $emailCollection;
        }

        $owner = $this->usersTable->findFirstForEmail($deletedBy);

        foreach ($users as $user) {
            if ($user->isDisabled()) {
                continue;
            }
            $emailCollection->addEmail($this->createDeleteEmail($user, $owner, $resource));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Email of the recipient user
     * @param \App\Model\Entity\User $owner User who executed the action
     * @param \App\Model\Entity\Resource $resource Resource
     * @return \App\Notification\Email\Email
     */
    private function createDeleteEmail(User $recipient, User $owner, Resource $resource): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($owner, $resource) {
                return __(
                    '{0} deleted the password {1}',
                    Purifier::clean($owner->profile->first_name),
                    Purifier::clean($resource->name)
                );
            }
        );
        $data = [
            'body' => [
                'user' => $owner,
                'subject' => $subject,
                'resource' => $resource,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
            ],
            'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
