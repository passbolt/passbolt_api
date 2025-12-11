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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Notification\Email\Redactor;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\ResourcesTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\MetadataKeyCreateService;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class MetadataKeyCreateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const EMAIL_TEMPLATE = 'Passbolt/Metadata.Admin/metadata_key_create';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected ResourcesTable $Resources;

    /**
     * MetadataKeyCreateEmailRedactor constructor.
     */
    public function __construct()
    {
        $this->Users = $this->fetchTable('Users');
        $this->Resources = $this->fetchTable('Resources');
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
            MetadataKeyCreateService::AFTER_METADATA_KEY_CREATE_SUCCESS_EVENT_NAME,
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
        $emailCollection = new EmailCollection();
        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
        $metadataKey = $event->getData('metadataKey');

        $admins = $this->Users->findAdmins();
        $resourcesCount = $this->Resources->find()->count();
        // Do not send email if metadata is enabled on the first setup
        if ($admins->count() === 1 && $resourcesCount === 0) {
            return $emailCollection;
        }

        $admins = $admins
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->find('notDisabled')
            ->find('locale');
        $modifier = $this->Users->findFirstForEmail($uac->getId());

        /** @var array<\App\Model\Entity\User> $admins */
        foreach ($admins as $recipient) {
            $email = $this->createEmail($recipient, $modifier, $metadataKey);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Admin being notified
     * @param \App\Model\Entity\User $modifier Admin who performed the action
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey settings DTO
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $modifier,
        MetadataKey $metadataKey
    ): Email {
        if ($recipient->id === $modifier->id) {
            $subject = $this->getSubjectForModifier($recipient);
        } else {
            $subject = $this->getSubjectForOtherAdmin($recipient, $modifier);
        }
        $fingerprint = $metadataKey->fingerprint;

        return new Email(
            $recipient,
            $subject,
            [
                'body' => compact('recipient', 'modifier', 'fingerprint', 'subject'),
                'title' => $subject,
            ],
            static::EMAIL_TEMPLATE
        );
    }

    /**
     * @param \App\Model\Entity\User $recipient User to include in the subject
     * @param \App\Model\Entity\User $modifier User performing the action
     * @return string
     */
    private function getSubjectForOtherAdmin(User $recipient, User $modifier): string
    {
        $modifierFirstName = $modifier['profile']['first_name'];

        return (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($modifierFirstName) {
                return __('{0} created a new metadata key', $modifierFirstName);
            }
        );
    }

    /**
     * @param \App\Model\Entity\User $recipient User performing the setting change
     * @return string
     */
    private function getSubjectForModifier(User $recipient): string
    {
        return (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('You created a new metadata key');
            }
        );
    }
}
