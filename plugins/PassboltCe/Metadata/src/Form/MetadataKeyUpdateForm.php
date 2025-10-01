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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Form;

use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\ArmoredKey\IsPublicKeyRevokedRule;
use App\Model\Validation\Fingerprint\IsMatchingKeyFingerprintValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Model\Validation\IsNullOnCreateRule;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

class MetadataKeyUpdateForm extends Form
{
    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('fingerprint', 'string')
            ->addField('armored_key', ['type' => 'string'])
            ->addField('expired', ['type' => 'datetime']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->setStopOnFailure()
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->maxLength('fingerprint', 51, __('A fingerprint should not be greater than 51 characters.'))
            ->notEmptyString('fingerprint', __('A fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'isValidFingerprint', new IsValidFingerprintValidationRule())
            ->add('fingerprint', 'isMatchingKeyFingerprint', new IsMatchingKeyFingerprintValidationRule());

        $validator
            ->setStopOnFailure()
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->add('armored_key', 'isParsableArmoredPublicKey', new IsParsableArmoredKeyValidationRule())
            ->add('armored_key', 'isPublicKeyRevoked', new IsPublicKeyRevokedRule());

        $validator
            ->requirePresence('expired')
            ->notEmptyDateTime('expired')
            /**
             * Front-end considers ISO8601 format as "YYYYYY-MM-DDTHH:mm:ss.sssZ" not the ATOM format.
             * Hence, we need another validation to try to parse it using strtotime() so other parts of the code can reliably parse the value.
             *
             * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/toISOString
             */
            ->dateTime('expired', [Validation::DATETIME_ISO8601], __('The expired field value should be a valid ISO 80601 date.')) // phpcs:ignore;
            ->add('expired', 'dateTimeStringParseable', [
                'rule' => [$this, 'isDateTimeStringParseable'],
                'message' => __('The expired field value should be parseable.'),
            ]);

        $validator
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'isNullOnCreate', new IsNullOnCreateRule());

        return $validator;
    }

    /**
     * Checks if given datetime string value is parseable via `strtotime()` function.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function isDateTimeStringParseable(string $check, array $context): bool
    {
        $value = strtotime($check);
        if (is_int($value)) {
            return true;
        }

        return false;
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
            'fingerprint' => $data['fingerprint'] ?? null,
            'armored_key' => $data['armored_key'] ?? null,
            'expired' => $data['expired'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];
    }
}
