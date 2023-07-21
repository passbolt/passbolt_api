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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Totp;

use App\Utility\UserAccessControl;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpVerifyForm extends MfaForm
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    protected $settings;

    /**
     * TotpVerifyForm constructor.
     *
     * @param \App\Utility\UserAccessControl $uac access control
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $settings setting
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        parent::__construct($uac);
        $this->settings = $settings;
    }

    /**
     * Build form schema
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('totp', ['type' => 'string']);
    }

    /**
     * Build form validation
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('totp', __('An OTP is required.'))
            ->notEmptyString('totp', __('The OTP should not be empty.'))
            ->add('totp', ['numeric' => [
                'rule' => 'numeric',
                'last' => true,
                'message' => __('The OTP should be composed of numbers only.'),
            ]])
            ->add('totp', ['minLength' => [
                'rule' => ['minLength', 6],
                'last' => true,
                'message' => __('The OTP should be at least {0} characters long.', 6),
            ]])
            ->add('totp', ['isValidOtp' => [
                'rule' => [$this, 'isValidOtp'],
                'message' => __('This OTP is not valid.'),
            ]]);

        return $validator;
    }

    /**
     * Custom validation rule to validate otp provisioning uri
     *
     * @param string $value otp provisioning uri
     * @return bool
     */
    public function isValidOtp(string $value)
    {
        if ($this->settings->getAccountSettings() === null) {
            return false;
        }

        return Factory::loadFromProvisioningUri($this->settings->getAccountSettings()->getOtpProvisioningUri())
            ->verify($value);
    }
}
