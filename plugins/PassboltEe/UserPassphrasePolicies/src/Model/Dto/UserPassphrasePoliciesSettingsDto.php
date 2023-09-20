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
 * @since         4.3.0
 */
namespace Passbolt\UserPassphrasePolicies\Model\Dto;

use Cake\I18n\FrozenTime;
use Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting;

class UserPassphrasePoliciesSettingsDto
{
    /**
     * Source default.
     *
     * @var string
     */
    public const SOURCE_DEFAULT = 'default';

    /**
     * Source db.
     *
     * @var string
     */
    public const SOURCE_DATABASE = 'db';

    /**
     * Default value of entropy minimum.
     *
     * @var int
     */
    public const ENTROPY_MINIMUM_DEFAULT = 50;

    /**
     * @var int|null
     */
    public $entropy_minimum;

    /**
     * @var bool|null
     */
    public $external_dictionary_check;

    /**
     * @var string|null
     */
    public $id;

    /**
     * @var \Cake\I18n\FrozenTime|null
     */
    public $created;

    /**
     * @var string|null
     */
    public $created_by;

    /**
     * @var \Cake\I18n\FrozenTime|null
     */
    public $modified;

    /**
     * @var string|null
     */
    public $modified_by;

    /**
     * @var string|null
     */
    public $source;

    /**
     * @param string|int|null $entropyMinimum Minimum entropy.
     * @param string|bool|null $externalDictionaryCheck External services check flag.
     * @param string|null $id ID.
     * @param \Cake\I18n\FrozenTime|null $created Created time.
     * @param string|null $createdBy Modified by.
     * @param \Cake\I18n\FrozenTime|null $modified Modified time.
     * @param string|null $modifiedBy Modified by.
     * @param string|null $source Source of these settings(can be db or default).
     */
    public function __construct(
        $entropyMinimum,
        $externalDictionaryCheck,
        ?string $id,
        ?FrozenTime $created,
        ?string $createdBy,
        ?FrozenTime $modified,
        ?string $modifiedBy,
        ?string $source
    ) {
        $this->entropy_minimum = (int)$entropyMinimum;
        $this->external_dictionary_check = (bool)$externalDictionaryCheck;
        $this->id = $id;
        $this->created = $created;
        $this->created_by = $createdBy;
        $this->modified = $modified;
        $this->modified_by = $modifiedBy;
        $this->source = $source;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data)
    {
        return new self(
            $data['entropy_minimum'] ?? null,
            $data['external_dictionary_check'] ?? null,
            $data['id'] ?? null,
            $data['created'] ?? null,
            $data['created_by'] ?? null,
            $data['modified'] ?? null,
            $data['modified_by'] ?? null,
            $data['source'] ?? null,
        );
    }

    /**
     * Returns object of itself from given entity.
     *
     * @param \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting $userPassphrasePoliciesSetting Entity object to create DTO from.
     * @return self
     */
    public static function createFromEntity(UserPassphrasePoliciesSetting $userPassphrasePoliciesSetting): self
    {
        $userPassphrasePoliciesSettingArray = $userPassphrasePoliciesSetting->toArray();

        /**
         * Flatten array to set settings value as root level keys.
         * Example: ['a', 'b', 'value' => ['c', 'd']] -> ['a', 'b', 'c', 'd']
         */
        $userPassphrasePoliciesSettingArray = array_merge(
            $userPassphrasePoliciesSettingArray,
            $userPassphrasePoliciesSettingArray['value']
        );
        unset($userPassphrasePoliciesSettingArray['value']);

        // Creating from an entity object hence it's from database.
        $userPassphrasePoliciesSettingArray['source'] = self::SOURCE_DATABASE;

        return self::createFromArray($userPassphrasePoliciesSettingArray);
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'entropy_minimum' => $this->entropy_minimum,
            'external_dictionary_check' => $this->external_dictionary_check,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
            'source' => $this->source,
        ];
    }

    /**
     * Same as `toArray` but unsets fields with `null` values.
     *
     * @return array
     */
    public function toFilteredArray(): array
    {
        return array_filter($this->toArray(), function ($value) {
            return !is_null($value);
        });
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toOrganizationSettingValueArray(): array
    {
        return [
            'entropy_minimum' => $this->entropy_minimum,
            'external_dictionary_check' => $this->external_dictionary_check,
        ];
    }

    /**
     * Create DTO from default policies settings.
     *
     * @param array $data Data to override.
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'entropy_minimum' => self::ENTROPY_MINIMUM_DEFAULT,
            'external_dictionary_check' => true,
            'source' => self::SOURCE_DEFAULT,
        ], $data));
    }
}
