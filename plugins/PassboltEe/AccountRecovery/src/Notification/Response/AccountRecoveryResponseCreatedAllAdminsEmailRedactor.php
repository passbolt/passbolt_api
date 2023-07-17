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
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

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
    protected $Users;

    /**
     * AccountRecoveryResponseCreatedAllAdminsEmailRedactor Constructor
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
            AccountRecoveryResponsesCreateService::RESPONSE_APPROVED_EVENT_NAME,
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

        /** @var \App\Model\Entity\User $actingAdmin */
        $actingAdmin = $this->Users->find()
            ->where(['Users.id' => $response->modified_by])
            ->contain('Profiles')
            ->firstOrFail();

        $admins = $this->Users->findAdmins()
            ->where(['Users.id <>' => $actingAdmin->id])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ]);

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->find()
            ->where(['Users.id' => $response->account_recovery_request->user_id])
            ->contain('Profiles')
            ->firstOrFail();

        foreach ($admins as $admin) {
            $emailCollection->addEmail($this->makeAdminEmail($user, $admin, $actingAdmin, $response));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User concerned
     * @param \App\Model\Entity\User $recipient Admin being notified
     * @param \App\Model\Entity\User $actingAdmin Admin approving the request
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response Account recovery response
     * @return \App\Notification\Email\Email
     */
    private function makeAdminEmail(
        User $user,
        User $recipient,
        User $actingAdmin,
        AccountRecoveryResponse $response
    ): Email {
        $status = $response->isApproved() ? __('approved') : __('rejected');
        $locale = (new GetUserLocaleService())->getLocale($recipient->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($status, $actingAdmin) {
                $firstName = $actingAdmin->profile->first_name;

                return __(
                    'Account recovery response set to {0} by {1}.',
                    $status,
                    Purifier::clean($firstName)
                );
            }
        );

        $data = ['body' => [
            'user' => $user,
            'admin' => $recipient,
            'actingAdmin' => $actingAdmin,
            'created' => $response->modified,
            'status' => $status,
        ], 'title' => $subject,];

        return new Email($recipient->username, $subject, $data, self::ALL_ADMIN_TEMPLATE);
    }
}
