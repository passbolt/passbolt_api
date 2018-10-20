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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Form;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Form\Schema;
use Cake\Network\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Yubikey\Validate;

class YubikeyVerifyForm extends MfaForm
{
    /**
     * @var MfaSettings
     */
    protected $settings;

    /**
     * VerifyForm constructor.
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
            ->addField('hotp', ['type' => 'string']);
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
            ->requirePresence('hotp', __('An OTP is required.'))
            ->notEmpty('hotp', __('The OTP should not be empty.'))
            ->add('hotp', ['isValidHotp' => [
                'rule' => [$this, 'isValidHotp'],
                'message' => __('This OTP is not valid.')
            ]]);

        return $validator;
    }

    /**
     * Custom validation rule to validate yubikey otp
     *
     * @param string $value hotp
     * @return bool
     */
    public function isValidHotp(string $value)
    {
        if (!Validation::custom($value, '/^[cbdefghijklnrtuv]{44}$/')) {
            return false;
        }
        try {
            $secretKey = $this->settings->getOrganizationSettings()->getYubikeyOTPSecretKey();
            $clientId = $this->settings->getOrganizationSettings()->getYubikeyOTPClientId();
        } catch(RecordNotFoundException $exception) {
            throw new InternalErrorException($exception->getMessage());
        }
        $request = new Validate($secretKey, $clientId);
        $response = $request->check($value);
        return $response->success();
    }
}
