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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Notification\Email\Redactor\Settings;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm;
use Passbolt\SelfRegistration\Service\SelfRegistrationSetSettingsService;

class SelfRegistrationSettingsAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const EMAIL_TEMPLATE = 'Passbolt/SelfRegistration.Admin/settings_update';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            SelfRegistrationSetSettingsService::SELF_REGISTRATION_SETTINGS_UPDATE_EVENT_NAME,
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
     * @todo [COULD] make another email for clarity when the settings are disabled.
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $modifiedById = $event->getData('modified_by');
        $provider = $event->getData('provider');
        $data = $event->getData('data');
        $status = __('Disabled');
        $info = null;
        if ($provider === SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS) {
            $status = __('Enabled');
            $allowedDomains = implode(', ', $data['allowed_domains']);
            $info = __('Allowed domains: {0}', $allowedDomains);
        }

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $modifier = $UsersTable->findFirstForEmail($modifiedById);
        $admins = $UsersTable
            ->findAdmins()
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->find('notDisabled')
            ->find('locale');

        foreach ($admins as $recipient) {
            $email = $this->createEmailAdminSettingsUpdate($recipient, $modifier, $status, $info);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient User to include in the subject
     * @param \App\Model\Entity\User $modifier User performing the action
     * @return string
     */
    private function getSubjectForOtherAdmin(User $recipient, User $modifier): string
    {
        $modifierFirstName = Purifier::clean($modifier['profile']['first_name']);

        return (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($modifierFirstName) {
                return __('{0} edited the self registration settings.', $modifierFirstName);
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
                return __('You edited the self registration settings.');
            }
        );
    }

    /**
     * @param \App\Model\Entity\User $recipient Admin being notified
     * @param \App\Model\Entity\User $modifier Admin who performed the action
     * @param string $status Enabled or disabled
     * @param ?string $info Info on the provider
     * @return \App\Notification\Email\Email
     */
    private function createEmailAdminSettingsUpdate(
        User $recipient,
        User $modifier,
        string $status,
        ?string $info
    ): Email {
        if ($recipient->id === $modifier->id) {
            $subject = $this->getSubjectForModifier($recipient);
        } else {
            $subject = $this->getSubjectForOtherAdmin($recipient, $modifier);
        }

        return new Email(
            $recipient,
            $subject,
            [
                'body' => compact('recipient', 'modifier', 'info', 'status', 'subject'),
                'title' => $subject,
            ],
            static::EMAIL_TEMPLATE
        );
    }
}
