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

namespace App\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class CoreNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @param \Cake\Form\Schema $schema An instance of schema
     * @return \Cake\Form\Schema
     */
    public function buildSchema(Schema $schema)
    {
        return $schema
            ->addField('purify_subject', ['type' => 'boolean', 'default' => false])
            // show controls
            ->addField('show_comment', ['type' => 'boolean', 'default' => true])
            ->addField('show_description', ['type' => 'boolean', 'default' => true])
            ->addField('show_secret', ['type' => 'boolean', 'default' => true])
            ->addField('show_uri', ['type' => 'boolean', 'default' => true])
            ->addField('show_username', ['type' => 'boolean', 'default' => true])
            // send controls
            ->addField('send_admin_user_setup_completed', ['type' => 'boolean', 'default' => true])
            ->addField('send_comment_add', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_add', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_manager_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_create', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_share', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_user_create', ['type' => 'boolean', 'default' => true])
            ->addField('send_user_recover', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param \Cake\Validation\Validator $validator An instance of validator
     * @return \Cake\Validation\Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator
            ->boolean('purify_subject', __('Purify subject should be a boolean.'))
            // Show controls
            ->boolean('show_comment', __('Show comment should be a boolean.'))
            ->boolean('show_description', __('Show description should be a boolean.'))
            ->boolean('show_secret', __('Show secret should be a boolean.'))
            ->boolean('show_uri', __('Show uri should be a boolean.'))
            ->boolean('show_username', __('Show username should be a boolean.'))
            // Send controls
            ->boolean('send_admin_user_setup_completed', __('An email notification setting should be a boolean.'))
            ->boolean('send_comment_add', __('Send comment add should be a boolean.'))
            ->boolean('send_group_delete', __('Send group delete should be a boolean.'))
            ->boolean('send_group_user_add', __('Send group user add should be a boolean.'))
            ->boolean('send_group_user_delete', __('Send group user delete should be a boolean.'))
            ->boolean('send_group_user_update', __('Send group user update should be a boolean.'))
            ->boolean('send_group_manager_update', __('Send group manager update should be a boolean.'))
            ->boolean('send_password_create', __('Send password create should be a boolean.'))
            ->boolean('send_password_share', __('Send password share should be a boolean.'))
            ->boolean('send_password_update', __('Send password update should be a boolean.'))
            ->boolean('send_password_delete', __('Send password delete should be a boolean.'))
            ->boolean('send_user_create', __('Send user create should be a boolean.'))
            ->boolean('send_user_recover', __('Send user recover should be a boolean.'));
    }
}
