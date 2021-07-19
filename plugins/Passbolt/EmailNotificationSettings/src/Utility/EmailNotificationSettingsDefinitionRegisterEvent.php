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

use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;

/**
 * @method \Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm getSubject()
 *  Event triggered to add new email notification settings definition.
 *  It contains the EmailNotificationSettingsForm, so it can be manipulated to add
 * new digest email notification settings definition at runtime.
 */
class EmailNotificationSettingsDefinitionRegisterEvent extends Event
{
    /**
     * Name of the event dispatched when registration of notification settings definition is run.
     */
    public const EVENT_NAME = 'email_notification_settings.definitions.register';

    /**
     * @param string $name Name of the event
     * @param \Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm $subject Subject of the dispatched event
     * @param null $data Data for the event
     */
    final public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof EmailNotificationSettingsForm) {
            $msg = '`subject` must be an instance of ' . EmailNotificationSettingsForm::class;
            throw new InvalidArgumentException($msg);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param \Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm $emailNotificationSettingsForm An instance of EmailNotificationSettingsForm
     * @return static
     */
    public static function create(EmailNotificationSettingsForm $emailNotificationSettingsForm)
    {
        return new static(static::EVENT_NAME, $emailNotificationSettingsForm);
    }

    /**
     * @return \Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm
     */
    public function getEmailNotificationSettingsForm()
    {
        return $this->getSubject();
    }
}
