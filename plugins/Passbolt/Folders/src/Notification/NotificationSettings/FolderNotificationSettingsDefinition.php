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

    const FOLDER_CREATE = 'send_folder_create';
    const FOLDER_DELETE = 'send_folder_delete';
    const FOLDER_UPDATE = 'send_folder_update';
    const FOLDER_SHARE = 'send_folder_share';

    const FIELDS = [
        self::FOLDER_DELETE,
        self::FOLDER_CREATE,
        self::FOLDER_UPDATE,
        self::FOLDER_SHARE,
    ];

    /**
     * @param Schema $schema An instance of schema
     * @return Schema
     */
    public function buildSchema(Schema $schema)
    {
        foreach (static::FIELDS as $fieldName) {
            $schema->addField($fieldName, ['type' => 'boolean', 'default' => true]);
        }

        return $schema;
    }

    /**
     * @param Validator $validator An instance of validator
     * @return Validator
     */
    public function buildValidator(Validator $validator)
    {
        foreach (static::FIELDS as $fieldName) {
            $validator->boolean($fieldName, __('An email notification setting should be a boolean.'));
        }

        return $validator;
    }
}
