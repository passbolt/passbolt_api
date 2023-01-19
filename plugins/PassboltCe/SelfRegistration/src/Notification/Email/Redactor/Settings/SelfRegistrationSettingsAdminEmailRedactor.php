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
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
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
     * @param \Cake\Event\Event $event User register event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $modifiedById = $event->getData('modified_by');
        $provider = $event->getData('provider');
        $data = $event->getData('data');
        $status = 'Disabled';
        $info = null;
        if ($provider === SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS) {
            $status = 'Enabled';
            $info = 'Allowed domains: ' . implode(', ', $data['allowed_domains']);
        }

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $modifier = $UsersTable->findFirstForEmail($modifiedById);

        $admins = $UsersTable
            ->findAdmins()
            ->find('locale')
            ->where(['Users.id !=' => $modifier->id]);

        foreach ($admins as $recipient) {
            $email = $this->createEmailAdminSettingsUpdate($recipient, $modifier, $status, $info);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient User to include in the subject
     * @return string
     */
    private function getSubject(User $recipient): string
    {
        return (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('Self registration settings update');
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
        return new Email(
            $recipient->username,
            $this->getSubject($recipient),
            [
                'body' => compact('recipient', 'modifier', 'info', 'status'),
                'title' => $this->getSubject($recipient),
            ],
            static::EMAIL_TEMPLATE
        );
    }
}
