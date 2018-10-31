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
namespace Passbolt\MultiFactorAuthentication\Form\Totp;

use App\Error\Exception\ValidationException;
use Cake\Form\Schema;
use Cake\Network\Exception\InternalErrorException;
use Cake\Validation\Validator;
use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupForm extends MfaForm
{
    /**
     * @var \OTPHP\TOTPInterface|\OTPHP\HOTPInterface
     */
    protected $otp;

    /**
     * Build form schema
     *
     * @param Schema $schema schema
     * @return $this|Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('otpProvisioningUri', ['type' => 'string'])
            ->addField('totp', ['type' => 'string']);
    }

    /**
     * Build form validation
     *
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->scalar('otpProvisioningUri')
            ->notEmpty('otpProvisioningUri')
            ->add('otpProvisioningUri', ['isValidOtpProvisioningUri' => [
                'rule' => [$this, 'isValidOtpProvisioningUri'],
                'message' => __('This OTP provision uri is not valid.')
            ]]);

        $validator
            ->requirePresence('totp', __('An OTP is required.'))
            ->notEmpty('totp', __('The OTP should not be empty.'))
            ->add('totp', ['isValidOtp' => [
                'rule' => [$this, 'isValidOtp'],
                'message' => __('This OTP is not valid.')
            ]]);

        return $validator;
    }

    /**
     * Custom validation rule to validate otp provisioning uri
     *
     * @param string $value otp provisioning uri
     * @return bool
     */
    public function isValidOtpProvisioningUri(string $value)
    {
        if (!is_string($value)) {
            return false;
        }
        try {
            $this->otp = Factory::loadFromProvisioningUri($value);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }

        return true;
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
        if (!isset($this->otp)) {
            return false;
        }
        if (!is_string($value)) {
            return false;
        }
        if (!is_numeric($value)) {
            return false;
        }

        return $this->otp->verify($value);
    }

    /**
     * Form post validation treatment
     *
     * @param array $data user submited data
     * @return bool
     */
    protected function _execute(array $data)
    {
        try {
            $data = ['otpProvisioningUri' => $data['otpProvisioningUri']];
            MfaAccountSettings::enableProvider($this->uac, MfaSettings::PROVIDER_TOTP, $data);
        } catch (ValidationException $e) {
            throw new InternalErrorException(__('Could not save the OTP settings. Please try again later.'));
        }

        return true;
    }
}
