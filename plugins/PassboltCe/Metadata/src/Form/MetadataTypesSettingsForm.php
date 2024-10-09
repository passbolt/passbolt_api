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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;

class MetadataTypesSettingsForm extends Form
{
    public const ALLOWED_VERSIONS = [
        MetadataTypesSettingsDto::V4,
        MetadataTypesSettingsDto::V5,
    ];

    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('default_resource_types', 'string')
            ->addField('default_folder_types', ['type' => 'string'])
            ->addField('default_tag_types', ['type' => 'string'])
            ->addField('default_comment_types', ['type' => 'string'])
            ->addField('allow_creation_of_v5_resources', ['type' => 'boolean'])
            ->addField('allow_creation_of_v5_folders', ['type' => 'boolean'])
            ->addField('allow_creation_of_v5_tags', ['type' => 'boolean'])
            ->addField('allow_creation_of_v5_comments', ['type' => 'boolean'])
            ->addField('allow_creation_of_v4_resources', ['type' => 'boolean'])
            ->addField('allow_creation_of_v4_folders', ['type' => 'boolean'])
            ->addField('allow_creation_of_v4_tags', ['type' => 'boolean'])
            ->addField('allow_creation_of_v4_comments', ['type' => 'boolean']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('default_resource_types', 'create', __('The setting is required.'))
            ->notEmptyString('default_resource_types', __('The setting should not be empty.'))
            ->utf8('default_resource_types', __('The setting should be a valid BMP-UTF8 string.'))
            ->inList('default_resource_types', self::ALLOWED_VERSIONS, __(
                'The setting should be one of the following: {0}.',
                implode(', ', self::ALLOWED_VERSIONS)
            ))
            ->add('default_resource_types', 'defaultTypeMustBeEnabled', [
                'rule' => function ($value, $context) {
                    if ($value === MetadataTypesSettingsDto::V5) {
                        return (!($context['data']['allow_creation_of_v5_resources'] ?? false)) === false;
                    } else {
                        return (!($context['data']['allow_creation_of_v4_resources'] ?? false)) === false;
                    }
                },
                'message' => __('The content type needs to be enabled in order to be set as default.'),
            ]);

        $validator
            ->requirePresence('default_folder_type', 'create', __('The setting is required.'))
            ->notEmptyString('default_folder_type', __('The setting should not be empty.'))
            ->utf8('default_folder_type', __('The setting should be a valid BMP-UTF8 string.'))
            ->inList('default_folder_type', self::ALLOWED_VERSIONS, __(
                'The setting should be one of the following: {0}.',
                implode(', ', self::ALLOWED_VERSIONS)
            ))
            ->add('default_folder_type', 'defaultTypeMustBeEnabled', [
                'rule' => function ($value, $context) {
                    if ($value === MetadataTypesSettingsDto::V5) {
                        return (!($context['data']['allow_creation_of_v5_folders'] ?? false)) === false;
                    } else {
                        return (!($context['data']['allow_creation_of_v4_folders'] ?? false)) === false;
                    }
                },
                'message' => __('The content type needs to be enabled in order to be set as default.'),
            ]);

        $validator
            ->requirePresence('default_comment_type', 'create', __('The setting is required.'))
            ->notEmptyString('default_comment_type', __('The setting should not be empty.'))
            ->utf8('default_comment_type', __('The setting should be a valid BMP-UTF8 string.'))
            ->inList('default_comment_type', self::ALLOWED_VERSIONS, __(
                'The setting should be one of the following: {0}.',
                implode(', ', self::ALLOWED_VERSIONS)
            ))
            ->add('default_comment_type', 'defaultTypeMustBeEnabled', [
                'rule' => function ($value, $context) {
                    if ($value === MetadataTypesSettingsDto::V5) {
                        return (!($context['data']['allow_creation_of_v5_comments'] ?? false)) === false;
                    } else {
                        return (!($context['data']['allow_creation_of_v4_comments'] ?? false)) === false;
                    }
                },
                'message' => __('The content type needs to be enabled in order to be set as default.'),
            ]);

        $validator
            ->requirePresence('default_tag_type', 'create', __('The setting is required.'))
            ->notEmptyString('default_tag_type', __('The setting should not be empty.'))
            ->utf8('default_tag_type', __('The setting should be a valid BMP-UTF8 string.'))
            ->inList('default_tag_type', self::ALLOWED_VERSIONS, __(
                'The setting should be one of the following: {0}.',
                implode(', ', self::ALLOWED_VERSIONS)
            ))
            ->add('default_tag_type', 'defaultTypeMustBeEnabled', [
                'rule' => function ($value, $context) {
                    if ($value === MetadataTypesSettingsDto::V5) {
                        return (!($context['data']['allow_creation_of_v5_tags'] ?? false)) === false;
                    } else {
                        return (!($context['data']['allow_creation_of_v4_tags'] ?? false)) === false;
                    }
                },
                'message' => __('The content type needs to be enabled in order to be set as default.'),
            ]);

        $validator
            ->boolean('allow_creation_of_v5_resources', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v5_resources', true, __('The setting is required.'));

        $validator
            ->boolean('allow_creation_of_v5_folders', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v5_folders', true, __('The setting is required.'));

        $validator
            ->boolean('allow_creation_of_v5_tags', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v5_tags', true, __('The setting is required.'));

        $validator
            ->boolean('allow_creation_of_v5_comments', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v5_comments', true, __('The setting is required.'));

        $validator
            ->boolean('allow_creation_of_v4_resources', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v4_resources', true, __('The setting is required.'))
            ->add('allow_creation_of_v4_resources', 'atLeastOne', [
                'rule' => function ($value, $context) {
                    $v5 = $context['data']['allow_creation_of_v5_resources'] ?? false;

                    return !(!$value && !$v5);
                },
                'message' => __('Both v4 and v5 settings cannot be disabled.'),
            ]);

        $validator
            ->boolean('allow_creation_of_v4_folders', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v4_folders', true, __('The setting is required.'))
            ->add('allow_creation_of_v4_folders', 'atLeastOne', [
                'rule' => function ($value, $context) {
                    $v5 = $context['data']['allow_creation_of_v5_folders'] ?? false;

                    return !(!$value && !$v5);
                },
                'message' => __('Both v4 and v5 settings cannot be disabled.'),
            ]);

        $validator
            ->boolean('allow_creation_of_v4_tags', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v4_tags', true, __('The setting is required.'))
            ->add('allow_creation_of_v4_tags', 'atLeastOne', [
                'rule' => function ($value, $context) {
                    $v5 = $context['data']['allow_creation_of_v5_tags'] ?? false;

                    return !(!$value && !$v5);
                },
                'message' => __('Both v4 and v5 settings cannot be disabled.'),
            ]);

        $validator
            ->boolean('allow_creation_of_v4_comments', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_creation_of_v4_comments', true, __('The setting is required.'))
            ->add('allow_creation_of_v4_comments', 'atLeastOne', [
                'rule' => function ($value, $context) {
                    $v5 = $context['data']['allow_creation_of_v5_comments'] ?? false;

                    return !(!$value && !$v5);
                },
                'message' => __('Both v4 and v5 settings cannot be disabled.'),
            ]);

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data);

        return parent::execute($data, $options);
    }

    /**
     * @param array $data Data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        $data = [
            'default_resource_types' => $data['default_resource_types'] ?? null,
            'default_folder_type' => $data['default_folder_type'] ?? null,
            'default_tag_type' => $data['default_tag_type'] ?? null,
            'default_comment_type' => $data['default_comment_type'] ?? null,
            'allow_creation_of_v5_resources' => $data['allow_creation_of_v5_resources'] ?? null,
            'allow_creation_of_v5_folders' => $data['allow_creation_of_v5_folders'] ?? null,
            'allow_creation_of_v5_comments' => $data['allow_creation_of_v5_comments'] ?? null,
            'allow_creation_of_v5_tags' => $data['allow_creation_of_v5_tags'] ?? null,
            'allow_creation_of_v4_resources' => $data['allow_creation_of_v4_resources'] ?? null,
            'allow_creation_of_v4_folders' => $data['allow_creation_of_v4_folders'] ?? null,
            'allow_creation_of_v4_comments' => $data['allow_creation_of_v4_comments'] ?? null,
            'allow_creation_of_v4_tags' => $data['allow_creation_of_v4_tags'] ?? null,
        ];

        $booleanFields = [
            'allow_creation_of_v5_resources',
            'allow_creation_of_v5_folders',
            'allow_creation_of_v5_comments',
            'allow_creation_of_v5_tags',
            'allow_creation_of_v4_resources',
            'allow_creation_of_v4_folders',
            'allow_creation_of_v4_comments',
            'allow_creation_of_v4_tags',
        ];
        foreach ($data as $field => $value) {
            // Convert values like '1', '0' to boolean data type
            if (in_array($field, $booleanFields) && Validation::boolean($value)) {
                $data[$field] = (bool)$value;
            }
        }

        return $data;
    }
}
