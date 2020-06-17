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

use App\Controller\Resources\ResourcesAddController;
use App\Model\Entity\Resource;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ResourceCreateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/resource_create';

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
            ResourcesAddController::ADD_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        /** @var Resource $resource */
        $resource = $event->getData('resource');

        $emailCollection->addEmail($this->createResourceCreateEmail($resource));

        return $emailCollection;
    }

    /**
     * @param resource $resource Resource
     * @return Email
     */
    private function createResourceCreateEmail(Resource $resource)
    {
        $user = $this->usersTable->findFirstForEmail($resource->created_by);
        $subject = __("You added the password {0}", $resource->name);
        $data = [
            'body' => [
                'user' => $user,
                'resource' => $resource,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
                'showSecret' => $this->getConfig('show.secret'),
            ], 'title' => $subject,
        ];

        return new Email($user->username, $subject, $data, self::TEMPLATE);
    }
}
