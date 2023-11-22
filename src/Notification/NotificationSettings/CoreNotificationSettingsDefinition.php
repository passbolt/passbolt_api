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
            ->addField('show_comment', ['type' => 'boolean', 'default' => false])
            ->addField('show_description', ['type' => 'boolean', 'default' => false])
            ->addField('show_secret', ['type' => 'boolean', 'default' => false])
            ->addField('show_uri', ['type' => 'boolean', 'default' => false])
            ->addField('show_username', ['type' => 'boolean', 'default' => false])
            // send controls
            ->addField('send_admin_user_setup_completed', ['type' => 'boolean', 'default' => true])
            ->addField('send_admin_user_recover_abort', ['type' => 'boolean', 'default' => true])
            ->addField('send_admin_user_recover_complete', ['type' => 'boolean', 'default' => true])
            ->addField('send_admin_user_disable_user', ['type' => 'boolean', 'default' => true])
            ->addField('send_admin_user_disable_admin', ['type' => 'boolean', 'default' => true])
            ->addField('send_comment_add', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_add', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_user_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_manager_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_group_manager_requestAddUser', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_create', ['type' => 'boolean', 'default' => false])
            ->addField('send_password_share', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_user_create', ['type' => 'boolean', 'default' => true])
            ->addField('send_user_recover', ['type' => 'boolean', 'default' => true])
            ->addField('send_user_recoverComplete', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param \Cake\Validation\Validator $validator An instance of validator
     * @return \Cake\Validation\Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator
            ->boolean('purify_subject', __('The purify subject setting should be a boolean.'))
            // Show controls
            ->boolean('show_comment', __('The show comment setting should be a boolean.'))
            ->boolean('show_description', __('The show description setting should be a boolean.'))
            ->boolean('show_secret', __('The show secret setting should be a boolean.'))
            ->boolean('show_uri', __('The show uri setting should be a boolean.'))
            ->boolean('show_username', __('The show username setting should be a boolean.'))
            // Send controls
            ->boolean(
                'send_admin_user_setup_completed',
                __('The send on user setup completed setting should be a boolean.')
            )
            ->boolean(
                'send_admin_user_recover_abort',
                __('The send on user recover abort setting should be a boolean.')
            )
            ->boolean('send_admin_user_disable_user', __('The send on user disabled setting should be a boolean.'))
            ->boolean('send_admin_user_disable_admin', __('The send on admin disabled setting should be a boolean.'))
            ->boolean('send_comment_add', __('The send on comment added setting should be a boolean.'))
            ->boolean('send_group_delete', __('The send on group deleted setting should be a boolean.'))
            ->boolean('send_group_user_add', __('The send on group user added setting should be a boolean.'))
            ->boolean('send_group_user_delete', __('The send on group user deleted setting should be a boolean.'))
            ->boolean('send_group_user_update', __('The send on group user updated setting should be a boolean.'))
            ->boolean('send_group_manager_update', __('The send on group manager updated setting should be a boolean.'))
            ->boolean(
                'send_group_manager_requestAddUser',
                __('The send on group manager request add user should be a boolean.')
            )
            ->boolean('send_password_create', __('The send on password created setting should be a boolean.'))
            ->boolean('send_password_share', __('The send on password shared setting should be a boolean.'))
            ->boolean('send_password_update', __('The send on password updated setting should be a boolean.'))
            ->boolean('send_password_delete', __('The send on password deleted setting should be a boolean.'))
            ->boolean('send_user_create', __('The send on user created setting should be a boolean.'))
            ->boolean('send_user_recover', __('The send on user recovered setting should be a boolean.'));
    }
}
