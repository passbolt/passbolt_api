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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Notification\Email\Redactor;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\JwtAuthentication\Error\Exception\AbstractJwtAttackException;
use Passbolt\JwtAuthentication\Error\Exception\Challenge\InvalidDomainException;
use Passbolt\JwtAuthentication\Error\Exception\Challenge\InvalidUserSignatureException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Error\Exception\VerifyToken\ConsumedVerifyTokenAccessException;
use Passbolt\Locale\Service\LocaleService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class JwtAuthenticationAttackEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * JwtAuthenticationAttackEmailRedactor constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed
     * and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            InvalidDomainException::class,
            InvalidUserSignatureException::class,
            ConsumedVerifyTokenAccessException::class,
            ConsumedRefreshTokenAccessException::class,
            ExpiredRefreshTokenAccessException::class,
            RefreshTokenNotFoundException::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @param \Cake\Event\Event $event User register event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        /** @var \Passbolt\JwtAuthentication\Error\Exception\AbstractJwtAttackException $exception */
        $exception = $event->getSubject();

        $emailCollection = new EmailCollection();

        $attackedUserId = $exception->getUserId();

        if (!empty($attackedUserId)) {
            $user = $this->Users->findFirstForEmail($attackedUserId);
            if (!empty($user)) {
                $this->addEmailToUser($emailCollection, $exception, $user);
                $this->addEmailToAdmins($emailCollection, $exception, $user);
            }
        }

        return $emailCollection;
    }

    /**
     * @param \App\Notification\Email\EmailCollection $collection User
     * @param \Passbolt\JwtAuthentication\Error\Exception\AbstractJwtAttackException $exception Exception triggering the email
     * @param \App\Model\Entity\User $user User targeted by the attack
     * @return void
     */
    private function addEmailToUser(
        EmailCollection $collection,
        AbstractJwtAttackException $exception,
        User $user
    ): void {
        $subject = (new LocaleService())->translateString(
            $user->locale,
            function () use ($exception) {
                return $exception->getUserEmailSubject();
            }
        );
        $email = new Email(
            $user,
            $subject,
            [
                'body' => [
                    'user' => $user,
                    'ip' => $exception->getController()->getRequest()->clientIp(),
                    'message' => $exception->getMessage(),
                ],
                'title' => $subject,
            ],
            $exception->getUserEmailTemplate()
        );

        $collection->addEmail($email);
    }

    /**
     * @param \App\Notification\Email\EmailCollection $collection User
     * @param \Passbolt\JwtAuthentication\Error\Exception\AbstractJwtAttackException $exception Exception triggering the email
     * @param \App\Model\Entity\User $user User targeted by the attack
     * @return void
     */
    private function addEmailToAdmins(
        EmailCollection $collection,
        AbstractJwtAttackException $exception,
        User $user
    ): void {
        $admins = $this->Users
            ->findAdmins()
            ->find('locale')
            ->find('notDisabled')
            ->where(['Users.id !=' => $user->id]);

        foreach ($admins as $admin) {
            $subject = (new LocaleService())->translateString(
                $admin->locale,
                function () use ($exception) {
                    return $exception->getAdminEmailSubject();
                }
            );
            $email = new Email(
                $admin,
                $subject,
                [
                    'body' => [
                        'user' => $user,
                        'ip' => $exception->getController()->getRequest()->clientIp(),
                        'message' => $exception->getMessage(),
                    ],
                    'title' => $subject,
                ],
                $exception->getAdminEmailTemplate()
            );
            $collection->addEmail($email);
        }
    }
}
