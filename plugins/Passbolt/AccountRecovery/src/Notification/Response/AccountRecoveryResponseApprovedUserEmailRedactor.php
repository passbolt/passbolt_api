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

namespace Passbolt\AccountRecovery\Notification\Response;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class AccountRecoveryResponseApprovedUserEmailRedactor implements SubscribedEmailRedactorInterface
{
    use ModelAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const USER_TEMPLATE = 'Passbolt/AccountRecovery.Responses/user_approved';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AccountRecoveryResponsesCreateService::RESPONSE_APPROVED_EVENT_NAME,
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
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response */
        $response = $event->getSubject();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findFirstForEmail($response->account_recovery_request->user_id);
        /** @var \App\Model\Entity\AuthenticationToken $authenticationToken */
        $authenticationToken = $this->Users->AuthenticationTokens->get(
            $response->account_recovery_request->authentication_token_id
        );
        /** @var \App\Model\Entity\User $admin */
        $admin = $this->Users->find()
            ->where(['Users.id' => $response->modified_by])
            ->contain('Profiles')
            ->firstOrFail();

        $emailCollection->addEmail($this->makeUserEmail($user, $admin, $response, $authenticationToken));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\User $admin Admin approving the request
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response Account recovery response
     * @param \App\Model\Entity\AuthenticationToken $authenticationToken The authentication token associated to the account recovery request
     * @return \App\Notification\Email\Email
     */
    private function makeUserEmail(
        User $user,
        User $admin,
        AccountRecoveryResponse $response,
        AuthenticationToken $authenticationToken
    ): Email {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () {
                return __('Recovery request approved!');
            }
        );

        $data = ['body' => [
            'user' => $user,
            'admin' => $admin,
            'created' => $response->modified,
            'authenticationToken' => $authenticationToken,
        ], 'title' => $subject,];

        return new Email($user->username, $subject, $data, self::USER_TEMPLATE);
    }
}
