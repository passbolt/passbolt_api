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

namespace App\Notification\Email\Redactor\User;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Comment;
use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\ResourcesTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class AccountRecoveryEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'AN/user_recover';

    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @param bool $isEnabled If notification is enabled or not
     */
    public function __construct(bool $isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'UsersRecoverController.recoverPost.success'
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        if (!$this->isEnabled) {
            return $emailCollection;
        }

        /** @var User $user */
        $user = $event->getData('user');
        /** @var AuthenticationToken $token */
        $token = $event->getData('token');

        $emailCollection->addEmail($this->createAccountRecoveryEmail($user, $token));

        return $emailCollection;
    }

    /**
     * @param User $user User
     * @param AuthenticationToken $token Token for recovery
     * @return Email
     */
    private function createAccountRecoveryEmail(User $user, AuthenticationToken $token)
    {
        $subject = __("Your account recovery, {0}!", $user->profile->first_name);
        $data = ['body' => ['user' => $user, 'token' => $token], 'title' => $subject];

        return new Email($user->username, $subject, $data, self::TEMPLATE);
    }
}
