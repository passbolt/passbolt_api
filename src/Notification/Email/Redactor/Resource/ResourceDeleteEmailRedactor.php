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

namespace App\Notification\Email\Redactor\Resource;

use App\Controller\Resources\ResourcesDeleteController;
use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ResourceDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/resource_delete';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var bool
     */
    private $showUsername;

    /**
     * @var bool
     */
    private $showUri;

    /**
     * @var bool
     */
    private $showDescription;

    /**
     * @param array           $config Configuration for the redactor
     * @param UsersTable|null $usersTable Users table
     */
    public function __construct(array $config = [], UsersTable $usersTable = null)
    {
        $this->setConfig($config);
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
            ResourcesDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event Resource update event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        /** @var Resource $resource */
        $resource = $event->getData('resource');
        /** @var string $deletedBy */
        $deletedBy = $event->getData('deletedBy');
        /** @var ResultSetInterface $users */
        $users = $event->getData('users');

        // if there is nobody or just one user, give it up
        if (count($users) < 2) {
            return $emailCollection;
        }

        $owner = $this->usersTable->findFirstForEmail($deletedBy);

        foreach ($users as $user) {
            if ($user->id === $deletedBy) {
                continue;
            }
            $emailCollection->addEmail($this->createDeleteEmail($user->username, $owner, $resource));
        }

        return $emailCollection;
    }

    /**
     * @param string   $emailRecipient Email of the recipient user
     * @param User     $owner User who executed the action
     * @param resource $resource Resource
     * @return Email
     */
    private function createDeleteEmail(string $emailRecipient, User $owner, Resource $resource)
    {
        $subject = __("{0} deleted the password {1}", $owner->profile->first_name, $resource->name);
        $data = [
            'body' => [
                'user' => $owner,
                'resource' => $resource,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
            ],
            'title' => $subject,
        ];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
