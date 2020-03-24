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
 * @since         2.14.0
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
     * @var bool
     */
    private $isEnabled;
    /**
     * @var mixed
     */
    private $showUsername;
    /**
     * @var mixed
     */
    private $showUri;
    /**
     * @var mixed
     */
    private $showDescription;
    /**
     * @var mixed
     */
    private $showSecret;

    /**
     * ResourceCreateEmailRedactor constructor.
     *
     * @param bool            $isEnabled Is Enabled
     * @param bool            $showUsername Show username in email
     * @param bool            $showUri Show uri in email
     * @param bool            $showDescription Show desc in email
     * @param bool            $showSecret Show secret in email
     * @param UsersTable|null $usersTable Users table
     */
    public function __construct(
        bool $isEnabled,
        bool $showUsername,
        bool $showUri,
        bool $showDescription,
        bool $showSecret,
        UsersTable $usersTable = null
    ) {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->isEnabled = $isEnabled;
        $this->showUsername = $showUsername;
        $this->showUri = $showUri;
        $this->showDescription = $showDescription;
        $this->showSecret = $showSecret;
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

        if (!$this->isEnabled) {
            return $emailCollection;
        }

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
                'showUsername' => $this->showUsername,
                'showUri' => $this->showUri,
                'showDescription' => $this->showDescription,
                'showSecret' => $this->showSecret,
            ], 'title' => $subject,
        ];

        return new Email($user->username, $subject, $data, self::TEMPLATE);
    }
}
