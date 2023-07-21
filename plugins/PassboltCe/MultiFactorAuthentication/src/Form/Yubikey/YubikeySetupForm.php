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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Yubikey;

use App\Error\Exception\ValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validator;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupForm extends YubikeyVerifyForm
{
    /**
     * Build form validation
     *
     * @param \Cake\Validation\Validator $validator validation rules
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('hotp', __('An OTP is required.'))
            ->notEmptyString('hotp', __('The OTP should not be empty.'))
            ->add('hotp', ['isValidModhex' => [
                'rule' => [$this, 'isValidModhex'],
                'last' => true,
                'message' => __('This OTP is not valid.'),
            ]])
            ->add('hotp', ['isValidHotp' => [
                'rule' => [$this, 'isValidHotp'],
                'message' => __('This OTP is not valid.'),
            ]]);

        return $validator;
    }

    /**
     * Form post validation treatment
     *
     * @param array $data user submited data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        try {
            // Save yubikey id to ensure next time use
            // @see https://developers.yubico.com/OTP/OTPs_Explained.html
            $keyid = substr($data['hotp'], 0, 12);
            $data = [MfaAccountSettings::YUBIKEY_ID => $keyid];
            MfaAccountSettings::enableProvider($this->uac, MfaSettings::PROVIDER_YUBIKEY, $data);
        } catch (ValidationException $e) {
            throw new InternalErrorException(
                'Could not save the Yubikey OTP settings. Please try again later.',
                500,
                $e
            );
        }

        return true;
    }
}
