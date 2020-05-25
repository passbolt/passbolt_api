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

use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Resources\ResourcesUpdateService;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ResourceUpdateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/resource_update';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param array      $config Configuration for the redactor
     * @param UsersTable $usersTable Users Table
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
            ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME,
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

        // Get the users that can access this resource
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$resource->id]]];
        $users = $this->usersTable->findIndex(Role::USER, $options)->all();
        $owner = $this->usersTable->findFirstForEmail($resource->modified_by);

        // Send emails to everybody that can see the resource
        foreach ($users as $user) {
            $emailCollection->addEmail($this->createUpdateEmail($user->username, $owner, $resource));
        }

        return $emailCollection;
    }

    /**
     * @param string   $emailRecipient Email of the recipient user
     * @param User     $owner User who executed the action
     * @param resource $resource Resource
     * @return Email
     */
    private function createUpdateEmail(string $emailRecipient, User $owner, Resource $resource)
    {
        $subject = __("{0} edited the password {1}", $owner->profile->first_name, $resource->name);
        $data = [
            'body' => [
                'user' => $owner,
                'resource' => $resource,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
                'showSecret' => $this->getConfig('show.secret'),
            ], 'title' => $subject,
        ];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
