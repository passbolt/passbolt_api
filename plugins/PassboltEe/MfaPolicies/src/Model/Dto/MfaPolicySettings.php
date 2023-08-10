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
 * @since         3.10.0
 */
namespace Passbolt\MfaPolicies\Model\Dto;

use Cake\I18n\FrozenTime;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;

class MfaPolicySettings
{
    /**
     * @var string|null
     */
    public $policy;

    /**
     * @var bool|null
     */
    public $remember_me_for_a_month;

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
     * Constructor.
     *
     * @param string|null $policy Policy.
     * @param bool|null $rememberMeForAMonth Remember me for a month.
     * @param string|null $id ID.
     * @param \Cake\I18n\FrozenTime|null $created Created time.
     * @param string|null $createdBy Created by.
     * @param \Cake\I18n\FrozenTime|null $modified Modified time.
     * @param string|null $modifiedBy Modified by.
     */
    public function __construct(
        ?string $policy,
        ?bool $rememberMeForAMonth,
        ?string $id,
        ?FrozenTime $created,
        ?string $createdBy,
        ?FrozenTime $modified,
        ?string $modifiedBy
    ) {
        $this->policy = $policy;
        $this->remember_me_for_a_month = $rememberMeForAMonth;
        $this->id = $id;
        $this->created = $created;
        $this->created_by = $createdBy;
        $this->modified = $modified;
        $this->modified_by = $modifiedBy;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data): self
    {
        /**
         * We get boolean string('1' or '0') from request sometimes. So to type cast it to native `bool` type of PHP.
         *
         * @link https://www.php.net/manual/en/language.types.boolean.php#language.types.boolean.casting
         */
        if (isset($data['remember_me_for_a_month']) && is_string($data['remember_me_for_a_month'])) {
            $data['remember_me_for_a_month'] = (bool)$data['remember_me_for_a_month'];
        }

        return new self(
            $data['policy'] ?? null,
            $data['remember_me_for_a_month'] ?? null,
            $data['id'] ?? null,
            $data['created'] ?? null,
            $data['created_by'] ?? null,
            $data['modified'] ?? null,
            $data['modified_by'] ?? null,
        );
    }

    /**
     * Returns object of itself from given entity.
     *
     * @param \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting $mfaPoliciesSetting Entity object to create DTO from.
     * @return self
     */
    public static function createFromEntity(MfaPoliciesSetting $mfaPoliciesSetting): self
    {
        $mfaPoliciesSettingArray = $mfaPoliciesSetting->toArray();

        /**
         * Flatten array to set settings value as root level keys.
         * Example: ['a', 'b', 'value' => ['c', 'd']] -> ['a', 'b', 'c', 'd']
         */
        $mfaPoliciesSettingArray = array_merge($mfaPoliciesSettingArray, $mfaPoliciesSettingArray['value']);
        unset($mfaPoliciesSettingArray['value']);

        return self::createFromArray($mfaPoliciesSettingArray);
    }
}
