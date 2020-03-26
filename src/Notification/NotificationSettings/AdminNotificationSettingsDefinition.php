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

namespace App\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class AdminNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @param Schema $schema An instance of schema
     * @return Schema
     */
    public function buildSchema(Schema $schema)
    {
        return $schema
            // send controls
            ->addField('send_admin_user_setup_completed', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param Validator $validator An instance of validator
     * @return Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator
            ->boolean('send_admin_user_setup_completed', __('An email notification setting should be a boolean.'));
    }
}
