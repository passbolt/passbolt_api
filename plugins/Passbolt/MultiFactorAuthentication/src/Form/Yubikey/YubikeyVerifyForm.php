<?php
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

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Form\Schema;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
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
     * @param UserAccessControl $uac access control
     * @param MfaSettings $settings settings
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        parent::__construct($uac);
        $this->settings = $settings;
    }

    /**
     * Build form schema
     *
     * @param Schema $schema schema
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
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('hotp', __('An OTP is required.'))
            ->notEmpty('hotp', __('The OTP should not be empty.'))
            ->add('hotp', ['isValidModhex' => [
                'rule' => [$this, 'isValidModhex'],
                'last' => true,
                'message' => __('This OTP is not valid.'),
            ]])
            ->add('hotp', ['isSameYubikeyId' => [
                'rule' => [$this, 'isSameYubikeyId'],
                'last' => true,
                'message' => __('This yubikey is not associated with this user.'),
            ]])
            ->add('hotp', ['isValidHotp' => [
                'rule' => [$this, 'isValidHotp'],
                'message' => __('This OTP is not valid.'),
            ]]);

        return $validator;
    }

    /**
     * Check if string match modehex format
     *
     * @param string $value value
     * @return bool
     */
    public function isValidModHex(string $value)
    {
        return (Validation::custom($value, '/^[cbdefghijklnrtuv]{44}$/'));
    }

    /**
     * Check if Yubikey Id match what is in account settings
     *
     * @param string $value value
     * @return bool
     */
    public function isSameYubikeyId(string $value)
    {
        $yubikeyId = substr($value, 0, 12);
        try {
            $yubikeyIdInSettings = $this->settings->getAccountSettings()->getYubikeyId();
        } catch (RecordNotFoundException $exception) {
            return false;
        }

        return ($yubikeyId === $yubikeyIdInSettings);
    }

    /**
     * Custom validation rule to validate yubikey otp using Yubicloud
     *
     * @param string $value hotp
     * @return bool
     */
    public function isValidHotp(string $value)
    {
        try {
            $secretKey = $this->settings->getOrganizationSettings()->getYubikeyOTPSecretKey();
            $clientId = $this->settings->getOrganizationSettings()->getYubikeyOTPClientId();
        } catch (RecordNotFoundException $exception) {
            throw new InternalErrorException($exception->getMessage());
        }
        $request = new Validate($secretKey, $clientId);
        $response = $request->check($value);

        return $response->success();
    }
}
