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
 * @since         2.13.0
 */

namespace Passbolt\EmailNotificationSettings\Utility;

use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DefaultEmailNotificationSettingsSource;

trait EmailNotificationSettingsDefinitionTrait
{
    /**
     * @see EmailNotificationSettingsDefinitionInterface::getDefaultSettingsSource()
     * @return \Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\ReadableEmailNotificationSettingsSourceInterface
     */
    public function getDefaultSettingsSource()
    {
        return DefaultEmailNotificationSettingsSource::fromSettingsFormDefinition($this);
    }

    /**
     * Return the event to listen on to register the current notification settings definition
     *
     * @return array<string, mixed>
     */
    public function implementedEvents(): array
    {
        return [
            EmailNotificationSettingsDefinitionRegisterEvent::EVENT_NAME => $this,
        ];
    }

    /**
     * An email notification settings definition must implement this method to register its definition into the EmailNotificationSettingsForm.
     *
     * @param \Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm $emailNotificationSettingsForm An instance instance of EmailNotificationSettingsForm.
     * @return void
     */
    public function addEmailNotificationSettingsDefinition(EmailNotificationSettingsForm $emailNotificationSettingsForm)
    {
        $emailNotificationSettingsForm->addEmailNotificationSettingsDefinition($this);
    }

    /**
     * @param \Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionRegisterEvent $event An instance of the event
     * @return void
     */
    public function __invoke(EmailNotificationSettingsDefinitionRegisterEvent $event)
    {
        $this->addEmailNotificationSettingsDefinition($event->getEmailNotificationSettingsForm());
    }
}
