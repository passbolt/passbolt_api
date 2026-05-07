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
 * @since         4.2.0
 */
namespace Passbolt\PasswordPoliciesUpdate\Model\Dto;

use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting;

class PasswordPoliciesUpdateSettingsDto extends PasswordPoliciesSettingsDto
{
    /**
     * Source db.
     *
     * @var string
     */
    public const SOURCE_DATABASE = 'db';

    /**
     * Returns object of itself from given entity.
     *
     * @param \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting $passwordPoliciesSetting Entity object to create DTO from.
     * @return self
     */
    public static function createFromEntity(PasswordPoliciesSetting $passwordPoliciesSetting): self
    {
        $passwordPoliciesSettingArray = $passwordPoliciesSetting->toArray();

        /**
         * Flatten array to set settings value as root level keys.
         * Example: ['a', 'b', 'value' => ['c', 'd']] -> ['a', 'b', 'c', 'd']
         */
        $passwordPoliciesSettingArray = array_merge(
            $passwordPoliciesSettingArray,
            $passwordPoliciesSettingArray['value']
        );
        unset($passwordPoliciesSettingArray['value']);

        // Creating from an entity object hence it's from database.
        $passwordPoliciesSettingArray['source'] = self::SOURCE_DATABASE;

        return parent::createFromArray($passwordPoliciesSettingArray);
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $data += [
            'id' => $this->id,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ];

        return $data;
    }

    /**
     * Returns an organization settings value array representation of the object.
     *
     * @return array
     */
    public function toOrganizationSettingValueArray(): array
    {
        return [
            'default_generator' => $this->default_generator,
            'password_generator_settings' => $this->password_generator_settings->toArray(),
            'passphrase_generator_settings' => $this->passphrase_generator_settings->toArray(),
            'external_dictionary_check' => $this->external_dictionary_check,
        ];
    }
}
