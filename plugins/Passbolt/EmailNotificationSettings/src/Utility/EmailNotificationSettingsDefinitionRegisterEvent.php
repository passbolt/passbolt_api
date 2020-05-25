<?php
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

use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;

/**
 * @method EmailNotificationSettingsForm getSubject()
 *
 * Event triggered to add new email notification settings definition. It contains the EmailNotificationSettingsForm, so it can be manipulated to add
 * new digest email notification settings definition at runtime.
 */
class EmailNotificationSettingsDefinitionRegisterEvent extends Event
{
    /**
     * Name of the event dispatched when registration of notification settings definition is run.
     */
    const EVENT_NAME = 'email_notification_settings.definitions.register';

    /**
     * @param string $name Name of the event
     * @param null $subject Subject of the dispatched event
     * @param null $data Data for the event
     */
    public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof EmailNotificationSettingsForm) {
            throw new InvalidArgumentException('`subject` must be an instance of ' . EmailNotificationSettingsForm::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param EmailNotificationSettingsForm $emailNotificationSettingsForm An instance of EmailNotificationSettingsForm
     * @return $this
     */
    public static function create(EmailNotificationSettingsForm $emailNotificationSettingsForm)
    {
        return new static(static::EVENT_NAME, $emailNotificationSettingsForm);
    }

    /**
     * @return EmailNotificationSettingsForm
     */
    public function getEmailNotificationSettingsForm()
    {
        return $this->getSubject();
    }
}
