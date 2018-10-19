<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Form;

use App\Error\Exception\ValidationException;
use Cake\Form\Schema;
use Cake\Network\Exception\InternalErrorException;
use Cake\Validation\Validator;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupForm extends MfaForm
{
    /**
     * Build form schema
     *
     * @param Schema $schema
     * @return $this|Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('otp', ['type' => 'string']);
    }

    /**
     * Build form validation
     *
     * @param Validator $validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->add('otp', ['isValidOtp' => [
                'rule' => [$this, 'isValidOtp'],
                'message' => __('This OTP is not valid.')
            ]]);

        return $validator;
    }

    /**
     *
     * Custom validation rule to validate otp provisioning uri
     *
     * @param string $value otp provisioning uri
     * @return bool
     */
    public function isValidOtp(string $value)
    {
        if (!is_string($value)) {
            return false;
        }
        // TODO Yubikey verify
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data)
    {
        try {
            MfaAccountSettings::enableProvider($this->uac , MfaSettings::PROVIDER_YUBIKEY);
        } catch (ValidationException $e) {
            throw new InternalErrorException(__('Could not save the yubikey settings. Please try again later.'));
        }

        return true;
    }
}