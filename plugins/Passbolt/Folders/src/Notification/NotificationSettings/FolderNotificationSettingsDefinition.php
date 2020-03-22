<?php declare(strict_types=1);

namespace Passbolt\Folders\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class FolderNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    const FOLDER_DELETED = 'send_folder_deleted';
    const FOLDER_CREATED = 'send_folder_created';
    const FOLDER_UPDATED = 'send_folder_updated';
    const FOLDER_SHARE_CREATED = 'send_folder_share_created';
    const FOLDER_SHARE_DROPPED = 'send_folder_share_dropped';

    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @param Schema $schema An instance of schema
     * @return Schema
     */
    public function buildSchema(Schema $schema)
    {
        return $schema
            // send controls
            ->addField(static::FOLDER_DELETED, ['type' => 'boolean', 'default' => true])
            ->addField(static::FOLDER_CREATED, ['type' => 'boolean', 'default' => true])
            ->addField(static::FOLDER_UPDATED, ['type' => 'boolean', 'default' => true])
            ->addField(static::FOLDER_SHARE_DROPPED, ['type' => 'boolean', 'default' => true])
            ->addField(static::FOLDER_SHARE_CREATED, ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @param Validator $validator An instance of validator
     * @return Validator
     */
    public function buildValidator(Validator $validator)
    {
        return $validator->boolean(static::FOLDER_DELETED, __('Send folder delete should be a boolean.'))
            ->boolean(static::FOLDER_CREATED, __('Send folder create should be a boolean.'))
            ->boolean(static::FOLDER_UPDATED, __('Send folder update should be a boolean.'))
            ->boolean(static::FOLDER_SHARE_DROPPED, __('Send folder share dropped should be a boolean.'))
            ->boolean(static::FOLDER_SHARE_CREATED, __('Send folder share created should be a boolean.'));
    }
}
