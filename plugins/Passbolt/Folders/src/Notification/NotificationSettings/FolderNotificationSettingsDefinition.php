<?php
declare(strict_types=1);

namespace Passbolt\Folders\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class FolderNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    const FOLDER_DELETED = 'send_folder_deleted';
    const FOLDER_CREATED = 'send_folder_created';
    const FOLDER_UPDATED = 'send_folder_updated';
    const FOLDER_SHARE_CREATED = 'send_folder_share_created';
    const FOLDER_SHARE_DROPPED = 'send_folder_share_dropped';

    const FIELDS = [
        self::FOLDER_DELETED,
        self::FOLDER_CREATED,
        self::FOLDER_UPDATED,
        self::FOLDER_SHARE_CREATED,
        self::FOLDER_SHARE_DROPPED,
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
