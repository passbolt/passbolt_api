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

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class EmailNotificationSettingsForm extends Form
{
    /**
     * Database configuration schema.
     *
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            // Show controls
            ->addField('show_comment', ['type' => 'boolean'])
            ->addField('show_description', ['type' => 'boolean'])
            ->addField('show_secret', ['type' => 'boolean'])
            ->addField('show_uri', ['type' => 'boolean'])
            ->addField('show_username', ['type' => 'boolean'])

            // Send controls
            ->addField('send_comment_add', ['type' => 'boolean'])
            ->addField('send_password_create', ['type' => 'boolean'])
            ->addField('send_password_share', ['type' => 'boolean'])
            ->addField('send_password_update', ['type' => 'boolean'])
            ->addField('send_password_delete', ['type' => 'boolean'])
            ->addField('send_user_create', ['type' => 'boolean'])
            ->addField('send_user_recover', ['type' => 'boolean'])
            ->addField('send_group_delete', ['type' => 'boolean'])
            ->addField('send_group_user_add', ['type' => 'boolean'])
            ->addField('send_group_user_delete', ['type' => 'boolean'])
            ->addField('send_group_user_update', ['type' => 'boolean'])
            ->addField('send_group_manager_update', ['type' => 'boolean']);
    }

    /**
     * Validation rules.
     *
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->boolean('show_comment', __('Show comment should be a boolean.'))
            ->boolean('show_description', __('Show description should be a boolean.'))
            ->boolean('show_secret', __('Show secret should be a boolean.'))
            ->boolean('show_uri', __('Show uri should be a boolean.'))
            ->boolean('show_username', __('Show username should be a boolean.'))
            ->boolean('send_comment_add', __('Send comment add should be a boolean.'))
            ->boolean('send_password_create', __('Send password create should be a boolean.'))
            ->boolean('send_password_share', __('Send password share should be a boolean.'))
            ->boolean('send_password_update', __('Send password update should be a boolean.'))
            ->boolean('send_password_delete', __('Send password delete should be a boolean.'))
            ->boolean('send_user_create', __('Send user create should be a boolean.'))
            ->boolean('send_user_recover', __('Send user recover should be a boolean.'))
            ->boolean('send_group_delete', __('Send group delete should be a boolean.'))
            ->boolean('send_group_user_add', __('Send group user add should be a boolean.'))
            ->boolean('send_group_user_delete', __('Send group user delete should be a boolean.'))
            ->boolean('send_group_user_update', __('Send group user update should be a boolean.'))
            ->boolean('send_group_manager_update', __('Send group manager update should be a boolean.'));

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
            $key = str_replace('_', '.', $prop);
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
