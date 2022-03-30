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

namespace Passbolt\AccountRecovery\Notification\Request;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestCreateService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class AccountRecoveryRequestCreatedUserEmailRedactor implements SubscribedEmailRedactorInterface
{
    use ModelAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const USER_TEMPLATE = 'Passbolt/AccountRecovery.Requests/user_request';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AccountRecoveryRequestCreateService::REQUEST_CREATED_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        $this->loadModel('Users');
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = $event->getSubject();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findFirstForEmail($request->user_id);

        $emailCollection->addEmail($this->makeUserEmail($user, $request));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request Account recovery request initiated by the user
     * @return \App\Notification\Email\Email
     */
    private function makeUserEmail(User $user, AccountRecoveryRequest $request): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () {
                return __('You have initiated a recovery request');
            }
        );

        $data = ['body' => ['user' => $user, 'created' => $request->created,], 'title' => $subject,];

        return new Email($user->username, $subject, $data, self::USER_TEMPLATE);
    }
}
