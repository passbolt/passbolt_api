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

class MetadataTypesSettingsDto
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
    ];

    public const ENTITY_RESOURCE = 'resource';
    public const ENTITY_FOLDER = 'folder';

    /**
     * @var array data
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param array|null $data data
     */
    public function __construct(?array $data = null)
    {
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
        ];
    }

    /**
     * @return bool
     */
    public function isV5ResourceCreationAllowed(): bool
    {
        return !empty($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]);
    }

    /**
     * @return bool
     */
    public function isV4ResourceCreationAllowed(): bool
    {
        return !empty($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
    }

    /**
     * @return bool
     */
    public function isV5FolderCreationAllowed(): bool
    {
        return !empty($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS]);
    }

    /**
     * @return bool
     */
    public function isV4FolderCreationAllowed(): bool
    {
        return !empty($this->data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
    }

    /**
     * @return ?array
     */
    public function toArray(): ?array
    {
        return $this->data;
    }

    /**
     * @return string self::data serialized as json string
     * @throws \JsonException if there is an issue with the data
     */
    public function toJson(): string
    {
        return json_encode($this->data, JSON_THROW_ON_ERROR);
    }
}
