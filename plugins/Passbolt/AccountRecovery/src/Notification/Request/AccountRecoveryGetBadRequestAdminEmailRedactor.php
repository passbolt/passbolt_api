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
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestGetService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class AccountRecoveryGetBadRequestAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use ModelAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const ADMIN_TEMPLATE = 'Passbolt/AccountRecovery.Requests/bad_request';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AccountRecoveryRequestGetService::ACCOUNT_RECOVERY_REQUEST_GET_BAD_REQUEST,
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

        $userId = $event->getData('userId');
        $requestId = $event->getData('requestId');
        $clientIp = $event->getData('clientIp');

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findFirstForEmail($userId);

        $admins = $this->Users->findAdmins()
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ]);
        foreach ($admins as $admin) {
            $emailCollection->addEmail($this->makeAdminEmail($admin, $user, $requestId, $clientIp));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $admin Admin receiving the mail
     * @param \App\Model\Entity\User $user User sending the request
     * @param string $requestId Bad request ID requested
     * @param string $clientIp Client IP
     * @return \App\Notification\Email\Email
     */
    private function makeAdminEmail(
        User $admin,
        User $user,
        string $requestId,
        string $clientIp
    ): Email {
        $locale = (new GetUserLocaleService())->getLocale($admin->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($clientIp, $user) {
                return __(
                    'Suspicious account recovery request issued from IP {0} for {1}',
                    $clientIp,
                    Purifier::clean($user->profile->first_name)
                );
            }
        );

        $data = ['body' => [
            'user' => $user,
            'admin' => $admin,
            'clientIp' => $clientIp,
            'requestId' => $requestId,
            'created' => FrozenTime::now(),
            'subject' => $subject,
        ], 'title' => $subject,];

        return new Email($admin->username, $subject, $data, self::ADMIN_TEMPLATE);
    }
}
