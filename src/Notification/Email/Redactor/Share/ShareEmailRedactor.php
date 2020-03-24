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

namespace App\Notification\Email\Redactor\Share;

use App\Controller\Share\ShareController;
use App\Model\Entity\Resource;
use App\Model\Entity\Secret;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ShareEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/resource_share';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var bool
     */
    private $isEnabled;

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
     * @var bool
     */
    private $showSecret;

    /**
     * @param bool            $isEnabled Is enabled
     * @param bool            $showUsername Show username in email
     * @param bool            $showUri Show uri in email
     * @param bool            $showDescription Show description in email
     * @param bool            $showSecret Show secret in email
     * @param UsersTable|null $usersTable Users Table
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
            ShareController::SHARE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        $resource = $event->getData('resource');
        $changes = $event->getData('changes');
        $ownerId = $event->getData('ownerId');

        if (!$this->isEnabled) {
            return $emailCollection;
        }

        // for now only handle the new share
        // e.g. we don't notify when permission changes or are removed
        $userIds = Hash::extract($changes['secrets'], '{n}.user_id');
        if (!empty($userIds)) {
            // Get the details of whoever did the changes
            $owner = $this->usersTable->findFirstForEmail($ownerId);
            $users = Hash::combine($this->getUserFromIds($userIds), '{n}.id', '{n}.username');
            $secrets = Hash::combine($changes['secrets'], '{n}.user_id', '{n}.data');

            foreach ($users as $userId => $userName) {
                $emailCollection->addEmail(
                    $this->createShareEmail($userName, $owner, $resource, $secrets[$userId])
                );
            }
        }

        return $emailCollection;
    }

    /**
     * Return a collection of users from a list of user ids
     *
     * @param array $userIds A list of user ids
     *
     * @return array
     */
    private function getUserFromIds(array $userIds)
    {
        return $this->usersTable->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $userIds])
            ->all()
            ->toArray();
    }

    /**
     * @param string   $emailRecipient Email of the user to send email to
     * @param User     $owner Owner
     * @param resource $resource Resource
     * @param string   $secret Secret
     * @return Email
     */
    private function createShareEmail(string $emailRecipient, User $owner, Resource $resource, string $secret)
    {
        $subject = __("{0} shared the password {1}", $owner->profile->first_name, $resource->name);

        $data = [
            'body' => [
                'owner' => $owner,
                'resource' => $resource,
                'secret' => $secret,
                'showUsername' => $this->showUsername,
                'showUri' => $this->showUri,
                'showDescription' => $this->showDescription,
                'showSecret' => $this->showSecret,
            ],
            'title' => $subject,
        ];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
