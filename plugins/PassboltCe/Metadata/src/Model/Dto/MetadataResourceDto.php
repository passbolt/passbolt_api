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
use Cake\Http\Exception\BadRequestException;
use Passbolt\Metadata\MetadataPlugin;

class MetadataResourceDto
{
    use FeaturePluginAwareTrait;

    public const NAME = 'name';
    public const USERNAME = 'username';
    public const URI = 'uri';
    public const DESCRIPTION = 'description';
    public const METADATA = 'metadata';
    public const METADATA_KEY_ID = 'metadata_key_id';
    public const METADATA_KEY_TYPE = 'metadata_key_type';
    public const DELETED = 'deleted';
    public const EXPIRED = 'expired';
    public const CREATED = 'created';
    public const MODIFIED = 'modified';
    public const CREATED_BY = 'created_by';
    public const MODIFIED_BY = 'modified_by';
    public const RESOURCE_TYPE_ID = 'resource_type_id';
    public const PERMISSIONS = 'permissions';
    public const SECRETS = 'secrets';

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
            throw new BadRequestException(__(
                'The following fields are required: {0}.',
                implode(', ', $v5MissingFields)
            ));
        }

        // Now that we know that we have a valid v5 payload, we check that no v4 fields are in the payload
        $v4SuperfluousFields = [];
        foreach (self::V4_META_PROPS as $v4Field) {
            if (array_key_exists($v4Field, $payload) && !is_null($payload[$v4Field])) {
                $v4SuperfluousFields[] = $v4Field;
            }
        }
        if (!empty($v4SuperfluousFields)) {
            throw new BadRequestException(__(
                'The following fields are not supported in v5: {0}.',
                implode(', ', $v4SuperfluousFields)
            ));
        }
    }
}
