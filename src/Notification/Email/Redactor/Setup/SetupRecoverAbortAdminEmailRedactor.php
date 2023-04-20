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
 * @since         3.6.0
 */

namespace App\Notification\Email\Redactor\Setup;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Setup\RecoverAbortService;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class SetupRecoverAbortAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;
    use ModelAwareTrait;

    public const TEMPLATE = 'AD/setup_recover_abort';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            RecoverAbortService::RECOVER_ABORT_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\User|null $user */
        $user = $event->getData('user') ?? null;
        if (!isset($user)) {
            return $emailCollection;
        }

        $this->loadModel('Users');

        // Create an email for every admin
        /** @var \App\Model\Entity\User[] $admins */
        $admins = $this->Users->findAdmins()->find('locale');
        foreach ($admins as $admin) {
            $email = $this->createAbortEmail($admin, $user);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $admin User
     * @param \App\Model\Entity\User $user User
     * @return \App\Notification\Email\Email
     */
    private function createAbortEmail(User $admin, User $user): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($user) {
                return __('{0} can not complete the account recovery process!', $user->profile->first_name);
            }
        );

        $data = ['body' => ['user' => $user], 'title' => $subject];

        return new Email($admin->username, $subject, $data, self::TEMPLATE);
    }
}
