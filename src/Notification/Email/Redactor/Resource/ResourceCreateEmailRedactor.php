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

    public const TEMPLATE = 'LU/resource_create';

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
            ResourcesAddController::ADD_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var Resource $resource */
        $resource = $event->getData('resource');

        $emailCollection->addEmail($this->createResourceCreateEmail($resource));

        return $emailCollection;
    }

    /**
     * @param Resource $resource Resource
     * @return \App\Notification\Email\Email
     */
    private function createResourceCreateEmail(Resource $resource)
    {
        $user = $this->usersTable->findFirstForEmail($resource->created_by);
        $subject = __('You added the password {0}', $resource->name);
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
