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

class YubikeyVerifyForm extends MfaForm
{
    /**
     * @var \OTPHP\TOTPInterface
     */
    protected $totp;

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
        parent::__construct($uac);
        $this->settings = $settings;
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
            ->addField('totp', ['type' => 'string']);
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
        if (!isset($this->totp)) {
            return false;
        }
        return $this->totp->verify($value);
    }
}