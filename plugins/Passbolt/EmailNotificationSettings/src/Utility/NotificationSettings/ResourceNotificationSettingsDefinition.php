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

namespace Passbolt\EmailNotificationSettings\Utility\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class ResourceNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @param Schema $schema An instance of schema
     * @return Schema
     */
    public function buildSchema(Schema $schema)
    {
        return $schema
            // show controls
            ->addField('show_description', ['type' => 'boolean', 'default' => true])
            ->addField('show_secret', ['type' => 'boolean', 'default' => true])
            ->addField('show_uri', ['type' => 'boolean', 'default' => true])
            ->addField('show_username', ['type' => 'boolean', 'default' => true])
            // send controls
            ->addField('send_password_create', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_share', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_password_delete', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param Validator $validator An instance of validator
     * @return Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator
            ->boolean('show_description', __('Show description should be a boolean.'))
            ->boolean('show_secret', __('Show secret should be a boolean.'))
            ->boolean('show_uri', __('Show uri should be a boolean.'))
            ->boolean('show_username', __('Show username should be a boolean.'))
            ->boolean('send_password_create', __('Send password create should be a boolean.'))
            ->boolean('send_password_share', __('Send password share should be a boolean.'))
            ->boolean('send_password_update', __('Send password update should be a boolean.'))
            ->boolean('send_password_delete', __('Send password delete should be a boolean.'));
    }
}
