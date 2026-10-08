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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Scim\Service\Cache\AbstractScimCacheService;

abstract class AbstractScimTokenExpiryEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @return \Passbolt\Scim\Service\Cache\AbstractScimCacheService
     */
    abstract protected function getCacheService(): AbstractScimCacheService;

    /**
     * @return string
     */
    abstract protected function getTemplate(): string;

    /**
     * @return string
     */
    abstract protected function getSubject(): string;

    /**
     * @return string|null
     */
    public function getNotificationSettingPath(): ?string
    {
        // always on
        return null;
    }

    /**
     * @param \Cake\Event\Event $event Event object.
     * @return \App\Notification\Email\EmailCollection
     * @throws \InvalidArgumentException When the event is missing expiry date.
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $expired = $event->getData('expired');
        if (empty($expired) || !is_string($expired)) {
            throw new InvalidArgumentException('`expired` is missing from event data.');
        }

        $cacheService = $this->getCacheService();
        if ($cacheService->isNotificationPerformed()) {
            return $emailCollection;
        }

        $recipients = $this->findAdminRecipients();
        if (count($recipients) === 0) {
            return $emailCollection;
        }

        $expiryDate = (new Date($expired))->toDateString();
        foreach ($recipients as $recipient) {
            $emailCollection->addEmail($this->createEmail($recipient, $expiryDate));
        }

        $cacheService->markNotificationPerformed();

        return $emailCollection;
    }

    /**
     * @return array<\App\Model\Entity\User>
     */
    private function findAdminRecipients(): array
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var array<\App\Model\Entity\User> $admins */
        $admins = $usersTable->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->toArray();

        return $admins;
    }

    /**
     * @param \App\Model\Entity\User $recipient Administrator to notify.
     * @param string $expiryDate Token expiry date.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, string $expiryDate): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return $this->getSubject();
            },
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'token_expiry_date' => $expiryDate,
                ],
                'title' => $subject,
            ],
            $this->getTemplate(),
        );
    }
}
