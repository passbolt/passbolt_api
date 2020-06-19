<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.10.0
 */
namespace Passbolt\EmailNotificationSettings\Form;

use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionRegisterEvent;

class EmailNotificationSettingsForm extends Form
{
    /**
     * @var EmailNotificationSettingsDefinitionInterface[]
     */
    private $notificationSettingsDefinitions = [];

    /**
     * @param EventManager|null $eventManager An instance of event manager
     */
    public function __construct(EventManager $eventManager = null)
    {
        parent::__construct($eventManager);

        $this->getEventManager()->dispatch(EmailNotificationSettingsDefinitionRegisterEvent::create($this));
    }

    /**
     * @param EmailNotificationSettingsDefinitionInterface $emailNotificationSettingsDefinition Email notification setting definition
     * @return void
     */
    public function addEmailNotificationSettingsDefinition(EmailNotificationSettingsDefinitionInterface $emailNotificationSettingsDefinition)
    {
        $this->notificationSettingsDefinitions[] = $emailNotificationSettingsDefinition;
    }

    /**
     * Database configuration schema. Build schema from all notification settings definitions schemas.
     *
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        foreach ($this->notificationSettingsDefinitions as $notificationSettingsDefinition) {
            $notificationSettingsDefinition->buildSchema($schema);
        }

        return $schema;
    }

    /**
     * Validation rules. Build validator rules from all notification settings definitions validators.
     *
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        foreach ($this->notificationSettingsDefinitions as $notificationSettingsDefinition) {
            $notificationSettingsDefinition->buildValidator($validator);
        }

        return $validator;
    }

    /**
     * Transform form data into the expected org settings format
     *
     * @param array $data The form data
     * @return array $settings The org settings data
     */
    public static function formatFormDataToOrgSettings(array $data = [])
    {
        if (empty($data)) {
            return $data;
        }

        $settings = [];
        $data = static::stripInvalidKeys($data);

        foreach ($data as $prop => $propVal) {
            $key = EmailNotificationSettings::underscoreToDottedFormat($prop);
            $settings[$key] = $propVal;
        }

        return $settings;
    }

    /**
     * Strip invalid email notification setting keys from the given $data array
     *
     * @param array $data The data array
     * @return array array with the invalid keys removed
     */
    public static function stripInvalidKeys(array $data = [])
    {
        if (empty($data)) {
            return $data;
        }

        foreach ($data as $prop => $propVal) {
            if (!EmailNotificationSettings::isConfigKeyValid($prop)) {
                unset($data[$prop]);
            }
        }

        return $data;
    }
}
