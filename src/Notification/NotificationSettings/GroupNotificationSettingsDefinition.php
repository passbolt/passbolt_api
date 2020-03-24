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
 * @since         2.14.0
 */

namespace App\Notification\NotificationSettings\Utility\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class GroupNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
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
            ->addField('send_group_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_add', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_manager_update', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param Validator $validator An instance of validator
     * @return Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator->boolean('send_group_delete', __('Send group delete should be a boolean.'))
            ->boolean('send_group_user_add', __('Send group user add should be a boolean.'))
            ->boolean('send_group_user_delete', __('Send group user delete should be a boolean.'))
            ->boolean('send_group_user_update', __('Send group user update should be a boolean.'))
            ->boolean('send_group_manager_update', __('Send group manager update should be a boolean.'));
    }
}
