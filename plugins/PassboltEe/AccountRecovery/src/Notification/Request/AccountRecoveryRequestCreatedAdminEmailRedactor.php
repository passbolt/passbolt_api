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
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestCreateService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

/**
 * Class AccountRecoveryRequestCreatedAdminEmailRedactor
 */
class AccountRecoveryRequestCreatedAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const ADMIN_TEMPLATE = 'Passbolt/AccountRecovery.Requests/admin_request';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * AccountRecoveryRequestCreatedAdminEmailRedactor Constructor
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
            AccountRecoveryRequestCreateService::REQUEST_CREATED_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.accountRecovery.request.admin';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = $event->getSubject();

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findFirstForEmail($request->user_id);

        $admins = $this->Users->findAdmins()
            ->find('notDisabled')
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])->all();

        // Notify users with the RBACS permission to view requests
        $admins = $this->appendAccountRecoveryRequestsViewers($admins);

        /** @var \App\Model\Entity\User $admin */
        foreach ($admins as $admin) {
            $emailCollection->addEmail($this->makeAdminEmail($admin, $user, $request));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $admin Admin receiving the mail
     * @param \App\Model\Entity\User $user User sending the request
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request Account recovery request initiated by the user
     * @return \App\Notification\Email\Email
     */
    private function makeAdminEmail(User $admin, User $user, AccountRecoveryRequest $request): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($admin->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($user) {
                return __('{0} has initiated a recovery request', $user->profile->full_name);
            }
        );

        $data = [
            'body' => [
               'user' => $user,
                'admin' => $admin,
                'created' => $request->created,
                'requestId' => $request->id,
            ],
            'title' => $subject,
        ];

        return new Email($admin, $subject, $data, self::ADMIN_TEMPLATE);
    }

    /**
     * Appends non-admin users who have RBAC permission to view account recovery requests.
     *
     * @param \Cake\Collection\CollectionInterface $admins Collection of admin users
     * @return \Cake\Collection\CollectionInterface Collection with additional users who have view permission via RBAC
     */
    protected function appendAccountRecoveryRequestsViewers(CollectionInterface $admins): CollectionInterface
    {
        if (!$this->Users->Roles->hasAssociation('Rbacs')) {
            return $admins;
        }

        $accountRecoveryRequestsViewers = $this->Users
            ->find('active')
            ->find('notDisabled')
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->contain('Roles', function (Query $q) {
                return $q->innerJoinWith('Rbacs', function (Query $q) {
                    $actionId = UuidFactory::uuid(
                        RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW
                    );

                    return $q->where([
                        'foreign_model' => Rbac::FOREIGN_MODEL_ACTION,
                        'foreign_id' => $actionId,
                        'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
                    ]);
                });
            });

        return $admins->append($accountRecoveryRequestsViewers->all());
    }
}
