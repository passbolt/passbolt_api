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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Network\Exception\InternalErrorException;
use Cake\Validation\Validator;
use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpVerifyForm extends Form
{
    /**
     * @var UserAccessControl
     */
    protected $uac;

    /**
     * @var \OTPHP\TOTPInterface|\OTPHP\HOTPInterface
     */
    protected $otp;

    /**
     * @var MfaSettings
     */
    protected $settings;

    /**
     * TotpVerifyForm constructor.
     * @param UserAccessControl $uac
     * @param MfaSettings $settings
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        $this->uac = $uac;
        $this->settings = $settings;
        $this->otp = Factory::loadFromProvisioningUri($settings->getOtpProvisioningUri());
    }

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
            ->requirePresence('otp', __('An OTP is required.'))
            ->notEmpty('otp', __('The OTP should not be empty.'))
            ->add('otp', ['numeric' => [
                'rule' => 'numeric',
                'last' => true,
                'message' => 'The OTP should be composed of numbers only.',
            ]])
            ->add('otp', ['minLength' => [
                'rule' => ['minLength', 6],
                'last' => true,
                'message' => 'The OTP should be at least 6 characters long',
            ]])
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
        if (!isset($this->otp)) {
            return false;
        }
        return $this->otp->verify($value);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }

    /**
     * Execute the form if it is valid.
     *
     * First validates the form, then calls the `_execute()` hook method.
     * This hook method can be implemented in subclasses to perform
     * the action of the form. This may be sending email, interacting
     * with a remote API, or anything else you may need.
     *
     * @param array $data Form data.
     * @return bool False on validation failure, otherwise returns the
     *   result of the `_execute()` method.
     */
    public function execute(array $data)
    {
        if (!$this->validate($data)) {
            throw new CustomValidationException(
                __('Something went wrong when validating the OTP.'),
                $this->errors()
            );
        }

        return $this->_execute($data);
    }

}