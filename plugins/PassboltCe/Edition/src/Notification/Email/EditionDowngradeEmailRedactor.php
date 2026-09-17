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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Edition\Service\EditionDowngradeService;
use Passbolt\Locale\Service\LocaleService;

/**
 * Notify every administrator when the instance is downgraded from PRO to CE.
 */
class EditionDowngradeEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'Passbolt/Edition.AD/edition_downgrade';

    private UsersTable $Users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @var \App\Model\Table\UsersTable $users */
        $users = $this->fetchTable('Users');
        $this->Users = $users;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            EditionDowngradeService::EVENT_EDITION_DOWNGRADED,
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
     * @param \Cake\Event\Event $event Edition.downgraded event.
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $uac = $event->getData('uac');
        if (!$uac instanceof UserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from Edition.downgraded event data.');
        }

        $operator = $this->Users->findFirstForEmail($uac->getId());
        if ($operator === null) {
            return $emailCollection;
        }

        $downgradedAt = DateTime::now();
        $recipients = $this->getAdministrators($uac->getId());

        foreach ($recipients as $recipient) {
            $emailCollection->addEmail($this->createEmail($recipient, $operator, $downgradedAt));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Email recipient.
     * @param \App\Model\Entity\User $operator Admin who triggered the downgrade.
     * @param \Cake\I18n\DateTime $downgradedAt Capture time of the downgrade.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $operator, DateTime $downgradedAt): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($operator): string {
                return __(
                    '{0} downgraded this Passbolt instance to Community Edition',
                    $operator->profile->full_name,
                );
            },
        );

        $operator->profile->setVirtual(['full_name']);

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'downgradedAt' => $downgradedAt,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE,
        );
    }

    /**
     * @param string $operatorId User id to exclude from the broadcast.
     * @return array<\App\Model\Entity\User>
     */
    private function getAdministrators(string $operatorId): array
    {
        /** @var array<\App\Model\Entity\User> $admins */
        $admins = $this->Users
            ->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->where(['Users.id !=' => $operatorId])
            ->toArray();

        return $admins;
    }
}
