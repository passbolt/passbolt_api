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

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class MetadataResourceDto extends MetadataDto
{
    use FeaturePluginAwareTrait;

    public const NAME = 'name';
    public const USERNAME = 'username';
    public const URI = 'uri';
    public const DESCRIPTION = 'description';
    public const DELETED = 'deleted';
    public const EXPIRED = 'expired';
    public const CREATED = 'created';
    public const MODIFIED = 'modified';
    public const CREATED_BY = 'created_by';
    public const MODIFIED_BY = 'modified_by';
    public const RESOURCE_TYPE_ID = 'resource_type_id';
    public const PERMISSIONS = 'permissions';
    public const SECRETS = 'secrets';
    public const CREATOR = 'creator';
    public const FOLDER_PARENT_ID = 'folder_parent_id';

    public const ALL_PROPS = [
        self::NAME,
        self::USERNAME,
        self::URI,
        self::DESCRIPTION,
        self::METADATA,
        self::METADATA_KEY_ID,
        self::METADATA_KEY_TYPE,
        self::DELETED,
        self::EXPIRED,
        self::CREATED,
        self::MODIFIED,
        self::CREATED_BY,
        self::MODIFIED_BY,
        self::RESOURCE_TYPE_ID,
        self::RESOURCE_TYPE_ID,
        self::PERMISSIONS,
        self::SECRETS,
        self::CREATOR,
        self::FOLDER_PARENT_ID,
    ];

    public const V4_META_PROPS = [
        self::NAME,
        self::USERNAME,
        self::URI,
        self::DESCRIPTION,
    ];

    public const V5_META_PROPS = [
        self::METADATA,
        self::METADATA_KEY_ID,
        self::METADATA_KEY_TYPE,
    ];

    private array $data = [];

    /**
     * @param array $data data passed in the request
     */
    public function __construct(array $data = [])
    {
        $this->validateRequestPayload($data);
        $isV5Enabled = $this->isFeaturePluginEnabled(MetadataPlugin::class);
        foreach (self::ALL_PROPS as $property) {
            if (in_array($property, self::V5_META_PROPS) && !$isV5Enabled) {
                continue;
            }
            if (array_key_exists($property, $data)) {
                $this->data[$property] = $data[$property];
            }
        }
    }

    /**
     * @param array $data Array data.
     * @return self
     * @throws \Cake\Http\Exception\BadRequestException If fields are not in valid form
     */
    public static function fromArray(array $data): self
    {
        return new MetadataResourceDto($data);
    }

    /**
     * @return bool
     */
    public function isV5(): bool
    {
        return array_key_exists(self::METADATA, $this->data) && !is_null($this->data[self::METADATA]);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return string[]
     */
    public function getMetadataProps(): array
    {
        return $this->isV5() ? self::V5_META_PROPS : self::V4_META_PROPS;
    }

    /**
     * @param array $payload data in the request
     * @throws \Cake\Http\Exception\BadRequestException if the payload is v5 but incomplete
     * @throws \Cake\Http\Exception\BadRequestException if v4 fields are set along with v5 fields
     * @return void
     */
    private function validateRequestPayload(array $payload): void
    {
        if (!$this->isFeaturePluginEnabled(MetadataPlugin::class)) {
            return;
        }
        // Check if any of the metadata fields is in the payload.
        // If not, we have a v4 payload.
        $isV4 = true;
        $v5MissingFields = [];
        foreach (self::V5_META_PROPS as $metadataField) {
            if (array_key_exists($metadataField, $payload) && !is_null($payload[$metadataField])) {
                $isV4 = false;
            } else {
                $v5MissingFields[] = $metadataField;
            }
        }
        if ($isV4) {
            return;
        }

        // Now that we know that we are in v5, we check that all the v5 metadata fields are set
        // If all v5 fields are not provided, throw an exception.
        if (!empty($v5MissingFields)) {
            $msg = __('Few fields are missing for the V5.');
            if (Configure::read('debug')) {
                Log::error($msg);
                Log::error(__('Missing fields: {0}', implode(', ', $v5MissingFields)));
            }

            throw new BadRequestException($msg);
        }

        // Now that we know that we have a valid v5 payload, we check that no v4 fields are in the payload
        $v4SuperfluousFields = [];
        foreach (self::V4_META_PROPS as $v4Field) {
            if (array_key_exists($v4Field, $payload) && !is_null($payload[$v4Field])) {
                $v4SuperfluousFields[] = $v4Field;
            }
        }
        if (!empty($v4SuperfluousFields)) {
            $msg = __('V4 related fields are not supported for V5.');
            if (Configure::read('debug')) {
                Log::error($msg);
                Log::error(__('Superfluous fields: {0}', implode(', ', $v4SuperfluousFields)));
            }

            throw new BadRequestException($msg);
        }
    }

    /**
     * Returns metadata array in cleartext form as per v5 format.
     *
     * @param bool $mapResourceType Should map resource type or not.
     * @return array
     */
    public function getClearTextMetadata(bool $mapResourceType = true): array
    {
        $resourceTypeId = $this->data[self::RESOURCE_TYPE_ID];
        if ($mapResourceType) {
            $mapping = ResourceType::getV5Mapping();

            $v4resourceTypeId = $this->data[self::RESOURCE_TYPE_ID];
            if (!isset($mapping[$v4resourceTypeId])) {
                throw new InternalErrorException(__('No resource type mapping for ID \'{0}\'', $v4resourceTypeId));
            }

            $resourceTypeId = $mapping[$v4resourceTypeId];
        }

        return [
            'object_type' => 'PASSBOLT_RESOURCE_METADATA',
            'resource_type_id' => $resourceTypeId,
            'name' => $this->data[self::NAME],
            'username' => $this->data[self::USERNAME],
            'uris' => [$this->data[self::URI]], // only 1 for now
            'description' => $this->data[self::DESCRIPTION],
            // below fields are null for now will be added in future
            'autofill_mappings' => null,
            'custom_fields' => null,
            'color' => null,
            'icon' => null,
        ];
    }
}
