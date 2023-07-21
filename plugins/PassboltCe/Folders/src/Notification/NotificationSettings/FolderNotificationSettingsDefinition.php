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
declare(strict_types=1);

namespace Passbolt\Folders\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class FolderNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @param \Cake\Form\Schema $schema An instance of schema
     * @return \Cake\Form\Schema
     */
    public function buildSchema(Schema $schema)
    {
        return $schema
            ->addField('send_folder_create', ['type' => 'boolean', 'default' => false])
            ->addField('send_folder_delete', ['type' => 'boolean', 'default' => true])
            ->addField('send_folder_update', ['type' => 'boolean', 'default' => true])
            ->addField('send_folder_share', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param \Cake\Validation\Validator $validator An instance of validator
     * @return \Cake\Validation\Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator
            ->boolean('send_folder_create', __('An email notification setting should be a boolean.'))
            ->boolean('send_folder_delete', __('An email notification setting should be a boolean.'))
            ->boolean('send_folder_update', __('An email notification setting should be a boolean.'))
            ->boolean('send_folder_share', __('An email notification setting should be a boolean.'));
    }
}
