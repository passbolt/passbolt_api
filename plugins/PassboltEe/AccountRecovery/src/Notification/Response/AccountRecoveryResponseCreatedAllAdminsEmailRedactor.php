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
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
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
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

/**
 * Class AccountRecoveryResponseCreatedAllAdminsEmailRedactor
 */
class AccountRecoveryResponseCreatedAllAdminsEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const ALL_ADMIN_TEMPLATE = 'Passbolt/AccountRecovery.Responses/created_all_admins';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * AccountRecoveryResponseCreatedAllAdminsEmailRedactor Constructor
     */
    public function __construct()
    {
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
            AccountRecoveryResponsesCreateService::RESPONSE_APPROVED_EVENT_NAME,
            AccountRecoveryResponsesCreateService::RESPONSE_REJECTED_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.accountRecovery.response.created.allAdmins';
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

        /** @var \App\Model\Entity\User $actingUser */
        $actingUser = $this->Users->find()
            ->where(['Users.id' => $response->modified_by])
            ->contain('Profiles')
            ->firstOrFail();

        $recipients = $this->Users
            ->find('adminsOrRbacActionGrantees', rbacActionName: RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW) // phpcs:ignore
            ->find('notDisabled')
            ->find('activeNotDeleted')
            ->where(['Users.id <>' => $actingUser->id])
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->all();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->find('notDisabled')
            ->where(['Users.id' => $response->account_recovery_request->user_id])
            ->contain('Profiles')
            ->firstOrFail();

        /** @var \App\Model\Entity\User $recipient */
        foreach ($recipients as $recipient) {
            $emailCollection->addEmail($this->makeAdminEmail($user, $recipient, $actingUser, $response));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User concerned
     * @param \App\Model\Entity\User $recipient User being notified
     * @param \App\Model\Entity\User $actingUser User who set the response status
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response Account recovery response
     * @return \App\Notification\Email\Email
     */
    private function makeAdminEmail(
        User $user,
        User $recipient,
        User $actingUser,
        AccountRecoveryResponse $response,
    ): Email {
        $status = $response->isApproved() ? __('approved') : __('rejected');
        $locale = (new GetUserLocaleService())->getLocale($recipient->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($status, $actingUser): string {
                return __(
                    'Account recovery response set to {0} by {1}.',
                    $status,
                    $actingUser->profile->first_name,
                );
            },
        );

        $data = [
            'body' => [
                'user' => $user,
                'admin' => $recipient,
                'actingAdmin' => $actingUser,
                'created' => $response->modified,
                'status' => $status,
            ],
            'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::ALL_ADMIN_TEMPLATE);
    }
}
