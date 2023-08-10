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

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

/**
 * Class AccountRecoveryResponseRejectedUserEmailRedactor
 */
class AccountRecoveryResponseRejectedUserEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const USER_TEMPLATE = 'Passbolt/AccountRecovery.Responses/user_rejected';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * AccountRecoveryResponseRejectedUserEmailRedactor Constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AccountRecoveryResponsesCreateService::RESPONSE_REJECTED_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response */
        $response = $event->getSubject();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findFirstForEmail($response->account_recovery_request->user_id);
        /** @var \App\Model\Entity\User $admin */
        $admin = $this->Users->find()
            ->where(['Users.id' => $response->modified_by])
            ->contain('Profiles')
            ->firstOrFail();

        $emailCollection->addEmail($this->makeUserEmail($user, $admin, $response));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\User $admin Admin approving the request
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response Account recovery response
     * @return \App\Notification\Email\Email
     */
    private function makeUserEmail(User $user, User $admin, AccountRecoveryResponse $response): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () {
                return __('Recovery request denied!');
            }
        );

        $data = ['body' => [
            'user' => $user,
            'admin' => $admin,
            'created' => $response->modified,
        ], 'title' => $subject,];

        return new Email($user->username, $subject, $data, self::USER_TEMPLATE);
    }
}
