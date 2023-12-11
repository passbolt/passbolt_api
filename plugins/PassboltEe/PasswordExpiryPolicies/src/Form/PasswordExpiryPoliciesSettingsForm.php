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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiryPolicies\Form;

use Cake\Validation\Validator;
use Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

class PasswordExpiryPoliciesSettingsForm extends PasswordExpirySettingsForm
{
    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(PasswordExpirySettingsDto::AUTOMATIC_EXPIRY)
            ->boolean(PasswordExpirySettingsDto::AUTOMATIC_EXPIRY);

        $validator
            ->requirePresence(PasswordExpirySettingsDto::AUTOMATIC_UPDATE)
            ->boolean(PasswordExpirySettingsDto::AUTOMATIC_UPDATE);

        $validator
            ->requirePresence(PasswordExpirySettingsDto::POLICY_OVERRIDE)
            ->boolean(PasswordExpirySettingsDto::POLICY_OVERRIDE);

        $validator
            ->requirePresence(PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD)
            ->allowEmptyString(PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD)
            ->naturalNumber(PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD);

        $validator
            ->requirePresence(PasswordExpirySettingsDto::EXPIRY_NOTIFICATION)
            ->allowEmptyString(PasswordExpirySettingsDto::EXPIRY_NOTIFICATION)
            ->naturalNumber(PasswordExpirySettingsDto::EXPIRY_NOTIFICATION);

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function sanitizeData(array $data): array
    {
        $castToInt = function (array $data, string $key): ?int {
            $value = $data[$key] ?? null;

            return is_null($value) ? null : intval($value);
        };

        return [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => $data[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY] ?? null,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => $data[PasswordExpirySettingsDto::AUTOMATIC_UPDATE] ?? null,
            PasswordExpirySettingsDto::POLICY_OVERRIDE => $data[PasswordExpirySettingsDto::POLICY_OVERRIDE] ?? null,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => $castToInt(
                $data,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD
            ),
            PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => $castToInt(
                $data,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION
            ),
        ];
    }
}
