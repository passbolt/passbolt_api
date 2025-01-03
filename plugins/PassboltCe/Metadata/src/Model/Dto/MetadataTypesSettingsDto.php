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
namespace Passbolt\Metadata\Model\Dto;

class MetadataTypesSettingsDto extends MetadataSettingsDto
{
    public const DEFAULT_RESOURCE_TYPES = 'default_resource_types';
    public const DEFAULT_FOLDER_TYPE = 'default_folder_type';
    public const DEFAULT_TAG_TYPE = 'default_tag_type';
    public const DEFAULT_COMMENT_TYPE = 'default_comment_type';
    public const ALLOW_CREATION_OF_V5_RESOURCES = 'allow_creation_of_v5_resources';
    public const ALLOW_CREATION_OF_V5_FOLDERS = 'allow_creation_of_v5_folders';
    public const ALLOW_CREATION_OF_V5_TAGS = 'allow_creation_of_v5_tags';
    public const ALLOW_CREATION_OF_V5_COMMENTS = 'allow_creation_of_v5_comments';
    public const ALLOW_CREATION_OF_V4_RESOURCES = 'allow_creation_of_v4_resources';
    public const ALLOW_CREATION_OF_V4_FOLDERS = 'allow_creation_of_v4_folders';
    public const ALLOW_CREATION_OF_V4_TAGS = 'allow_creation_of_v4_tags';
    public const ALLOW_CREATION_OF_V4_COMMENTS = 'allow_creation_of_v4_comments';
    public const ALLOW_V5_V4_DOWNGRADE = 'allow_v5_v4_downgrade';
    public const ALLOW_V4_V5_UPGRADE = 'allow_v4_v5_upgrade';
    public const V4 = 'v4';
    public const V5 = 'v5';

    public const PROPS = [
        self::DEFAULT_RESOURCE_TYPES,
        self::DEFAULT_FOLDER_TYPE,
        self::DEFAULT_TAG_TYPE,
        self::DEFAULT_COMMENT_TYPE,
        self::ALLOW_CREATION_OF_V5_RESOURCES,
        self::ALLOW_CREATION_OF_V5_FOLDERS,
        self::ALLOW_CREATION_OF_V5_TAGS,
        self::ALLOW_CREATION_OF_V5_COMMENTS,
        self::ALLOW_CREATION_OF_V4_RESOURCES,
        self::ALLOW_CREATION_OF_V4_FOLDERS,
        self::ALLOW_CREATION_OF_V4_TAGS,
        self::ALLOW_CREATION_OF_V4_COMMENTS,
        self::ALLOW_V5_V4_DOWNGRADE,
        self::ALLOW_V4_V5_UPGRADE,
    ];

    public const ENTITY_RESOURCE = 'resource';
    public const ENTITY_FOLDER = 'folder';
    public const ENTITY_TAG = 'tag';

    /**
     * Constructor.
     *
     * @param array|null $data data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        $this->data = [
            self::DEFAULT_RESOURCE_TYPES => $data[self::DEFAULT_RESOURCE_TYPES] ?? null,
            self::DEFAULT_FOLDER_TYPE => $data[self::DEFAULT_FOLDER_TYPE] ?? null,
            self::DEFAULT_TAG_TYPE => $data[self::DEFAULT_TAG_TYPE] ?? null,
            self::DEFAULT_COMMENT_TYPE => $data[self::DEFAULT_COMMENT_TYPE] ?? null,
            self::ALLOW_CREATION_OF_V5_RESOURCES => $data[self::ALLOW_CREATION_OF_V5_RESOURCES] ?? null,
            self::ALLOW_CREATION_OF_V5_FOLDERS => $data[self::ALLOW_CREATION_OF_V5_FOLDERS] ?? null,
            self::ALLOW_CREATION_OF_V5_TAGS => $data[self::ALLOW_CREATION_OF_V5_TAGS] ?? null,
            self::ALLOW_CREATION_OF_V5_COMMENTS => $data[self::ALLOW_CREATION_OF_V5_COMMENTS] ?? null,
            self::ALLOW_CREATION_OF_V4_RESOURCES => $data[self::ALLOW_CREATION_OF_V4_RESOURCES] ?? null,
            self::ALLOW_CREATION_OF_V4_FOLDERS => $data[self::ALLOW_CREATION_OF_V4_FOLDERS] ?? null,
            self::ALLOW_CREATION_OF_V4_TAGS => $data[self::ALLOW_CREATION_OF_V4_TAGS] ?? null,
            self::ALLOW_CREATION_OF_V4_COMMENTS => $data[self::ALLOW_CREATION_OF_V4_COMMENTS] ?? null,
            self::ALLOW_V5_V4_DOWNGRADE => $data[self::ALLOW_V5_V4_DOWNGRADE] ?? null,
            self::ALLOW_V4_V5_UPGRADE => $data[self::ALLOW_V4_V5_UPGRADE] ?? null,
        ];
    }

    /**
     * @return bool
     */
    public function isV5ResourceCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES] === true;
    }

    /**
     * @return bool
     */
    public function isV4ResourceCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES] === true;
    }

    /**
     * @return bool
     */
    public function isV5FolderCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS] === true;
    }

    /**
     * @return bool
     */
    public function isV4FolderCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES] === true;
    }

    /**
     * @return bool
     */
    public function isV5TagCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS] === true;
    }

    /**
     * @return bool
     */
    public function isV4TagCreationAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS])
            && $this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS] === true;
    }

    /**
     * @return bool
     */
    public function isV4DowngradeAllowed(): bool
    {
        return isset($this->data[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE])
            && $this->data[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE] === true;
    }

    /**
     * @return ?array
     */
    public function toHumanReadableArray(): ?array
    {
        $data = [];
        foreach (self::PROPS as $prop) {
            switch ($prop) {
                case self::DEFAULT_RESOURCE_TYPES:
                    $data[__('Default resource types')] = $this->data[self::DEFAULT_RESOURCE_TYPES];
                    break;
                case self::DEFAULT_FOLDER_TYPE:
                    $data[__('Default folder type')] = $this->data[self::DEFAULT_FOLDER_TYPE];
                    break;
                case self::DEFAULT_TAG_TYPE:
                    $data[__('Default folder type')] = $this->data[self::DEFAULT_TAG_TYPE];
                    break;
                case self::DEFAULT_COMMENT_TYPE:
                    $data[__('Default comment type')] = $this->data[self::DEFAULT_COMMENT_TYPE];
                    break;
                case self::ALLOW_CREATION_OF_V5_RESOURCES:
                    $data[__('Allow creation of V5 resources')] = $this->data[self::ALLOW_CREATION_OF_V5_RESOURCES] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V5_FOLDERS:
                    $data[__('Allow creation of V5 folders')] = $this->data[self::ALLOW_CREATION_OF_V5_FOLDERS] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V5_TAGS:
                    $data[__('Allow creation of V5 tags')] = $this->data[self::ALLOW_CREATION_OF_V5_TAGS] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V5_COMMENTS:
                    $data[__('Allow creation of V5 comments')] = $this->data[self::ALLOW_CREATION_OF_V5_COMMENTS] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V4_RESOURCES:
                    $data[__('Allow creation of V4 resources')] = $this->data[self::ALLOW_CREATION_OF_V5_RESOURCES] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V4_FOLDERS:
                    $data[__('Allow creation of V4 folders')] = $this->data[self::ALLOW_CREATION_OF_V4_FOLDERS] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V4_TAGS:
                    $data[__('Allow creation of V4 tags')] = $this->data[self::ALLOW_CREATION_OF_V4_TAGS] ?
                        __('True') : __('False');
                    break;
                case self::ALLOW_CREATION_OF_V4_COMMENTS:
                    $data[__('Allow creation of V5 comments')] = $this->data[self::ALLOW_CREATION_OF_V4_COMMENTS] ?
                        __('True') : __('False');
                    break;
            }
        }

        return $data;
    }
}
