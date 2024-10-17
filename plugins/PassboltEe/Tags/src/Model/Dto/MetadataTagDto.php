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
namespace Passbolt\Tags\Model\Dto;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Passbolt\Metadata\MetadataPlugin;

class MetadataTagDto
{
    use FeaturePluginAwareTrait;

    private ?string $slug;

    private ?bool $isShared;

    private ?string $metadata;

    private ?string $metadataKeyId;

    private ?string $metadataKeyType;

    public const V4_META_PROPS = ['slug'];

    public const V5_META_PROPS = [
        'metadata',
        'metadata_key_id',
        'metadata_key_type',
    ];

    /**
     * @param string|null $slug Slug.
     * @param bool|null $isShared Is shared.
     * @param string|null $metadata Metadata.
     * @param string|null $metadataKeyId Metadata key identifier.
     * @param string|null $metadataKeyType Metadata key type.
     * @throws \Cake\Http\Exception\BadRequestException If the data provided is v5 but incomplete
     * @throws \Cake\Http\Exception\BadRequestException If v4 fields are set along with v5 fields
     */
    public function __construct(
        ?string $slug = null,
        ?bool $isShared = null,
        ?string $metadata = null,
        ?string $metadataKeyId = null,
        ?string $metadataKeyType = null
    ) {
        $this->slug = $slug;
        $this->isShared = $isShared;
        $this->metadata = $metadata;
        $this->metadataKeyId = $metadataKeyId;
        $this->metadataKeyType = $metadataKeyType;

        $this->validate($this->toArray());
    }

    /**
     * @param array $data Array data.
     * @return self
     * @throws \Cake\Http\Exception\BadRequestException If the data provided is v5 but incomplete
     * @throws \Cake\Http\Exception\BadRequestException If v4 fields are set along with v5 fields
     */
    public static function fromArray(array $data): self
    {
        $name = Hash::get($data, 'slug');
        $isShared = Hash::get($data, 'is_shared');
        $metadata = Hash::get($data, 'metadata');
        $metadataKeyId = Hash::get($data, 'metadata_key_id');
        $metadataKeyType = Hash::get($data, 'metadata_key_type');

        /**
         * We get boolean string('1' or '0') from request sometimes. So to type cast it to native `bool` type of PHP.
         *
         * @link https://www.php.net/manual/en/language.types.boolean.php#language.types.boolean.casting
         */
        if (isset($isShared) && is_string($isShared)) {
            $isShared = (bool)$isShared;
        }

        return new self($name, $isShared, $metadata, $metadataKeyId, $metadataKeyType);
    }

    /**
     * @return bool
     */
    public function isV5(): bool
    {
        return !is_null($this->metadata);
    }

    /**
     * Returns if tag is shared or not.
     *
     * @return bool
     */
    public function isPersonal(): bool
    {
        if (!$this->isV5()) {
            // @codingStandardsIgnoreStart
            return @mb_substr($this->slug, 0, 1, 'utf-8') !== '#';
            // @codingStandardsIgnoreEnd
        }

        return !$this->isShared;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'is_shared' => $this->isShared,
            'metadata' => $this->metadata,
            'metadata_key_id' => $this->metadataKeyId,
            'metadata_key_type' => $this->metadataKeyType,
        ];
    }

    /**
     * @param array $data Data in the request.
     * @throws \Cake\Http\Exception\BadRequestException if the payload is v5 but incomplete
     * @throws \Cake\Http\Exception\BadRequestException if v4 fields are set along with v5 fields
     * @return void
     */
    private function validate(array $data): void
    {
        if (!$this->isFeaturePluginEnabled(MetadataPlugin::class)) {
            // Set v5 fields to null since metadata plugin is disabled
            $this->metadata = null;
            $this->metadataKeyId = null;
            $this->metadataKeyType = null;

            return;
        }

        // Check if any of the metadata fields is in the payload.
        // If not, we have a v4 payload.
        $isV4 = true;
        $v5MissingFields = [];
        foreach (self::V5_META_PROPS as $metadataField) {
            if (array_key_exists($metadataField, $data) && !is_null($data[$metadataField])) {
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
            if (array_key_exists($v4Field, $data) && !is_null($data[$v4Field])) {
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
     * @return array
     */
    public function getClearTextMetadata(): array
    {
        return [
            'object_type' => 'PASSBOLT_TAG_METADATA',
            'slug' => $this->slug,
            // below fields are null for now will be added in future
            'color' => null,
            'description' => null,
            'icon' => null,
        ];
    }
}
