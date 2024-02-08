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
namespace Passbolt\PasswordExpiry\Form;

use Cake\Form\Form;
use Cake\Validation\Validator;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

class PasswordExpirySettingsForm extends Form
{
    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(PasswordExpirySettingsDto::AUTOMATIC_EXPIRY)
            ->boolean(PasswordExpirySettingsDto::AUTOMATIC_EXPIRY)
            ->inList(
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY,
                [true],
                __('The automatic_expiry field must be true.')
            );

        $validator
            ->requirePresence(PasswordExpirySettingsDto::AUTOMATIC_UPDATE)
            ->boolean(PasswordExpirySettingsDto::AUTOMATIC_UPDATE)
            ->inList(
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE,
                [true],
                __('The automatic_update field must be true.')
            );

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
        return [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => $data[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY] ?? null,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => $data[PasswordExpirySettingsDto::AUTOMATIC_UPDATE] ?? null,
        ];
    }
}
