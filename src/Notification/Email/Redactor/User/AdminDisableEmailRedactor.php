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
 * @since         4.3.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;

/**
 * Class AdminDisableEmailRedactor
 */
class AdminDisableEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersEditController::EVENT_ADMIN_WAS_DISABLED,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.admin.user.disable.admin';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = $this->fetchTable('Users');

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        /** @var \App\Utility\UserAccessControl $operator */
        $operator = $event->getData('operator');

        $recipient = $UsersTable->findFirstForEmail($user->id);
        // Set the disabled field to the future so the email is well sent
        // as the Email class will not send mails to disabled users
        $recipient->set('disabled', FrozenTime::tomorrow());

        $email = $this->createEmail($recipient, $operator);

        return (new EmailCollection())->addEmail($email);
    }

    /**
     * @param \App\Model\Entity\User $user recipient
     * @param \App\Utility\UserAccessControl $operator admin the user might want to contact
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $user, UserAccessControl $operator): Email
    {
        $subject = (new LocaleService())->translateString(
            $user->locale,
            function () {
                return __('Your account has been suspended');
            }
        );
        $operatorUsername = $operator->getUsername();

        return new Email(
            $user,
            $subject,
            ['body' => compact('user', 'operatorUsername'), 'title' => $subject],
            'AD/admin_disable'
        );
    }
}
