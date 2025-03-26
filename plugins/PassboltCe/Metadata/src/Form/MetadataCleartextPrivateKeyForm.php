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

use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Exception;

class MetadataCleartextPrivateKeyForm extends Form
{
    public const PASSBOLT_METADATA_PRIVATE_KEY = 'PASSBOLT_METADATA_PRIVATE_KEY';

    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('object_type', ['type' => 'string'])
            ->addField('fingerprint', ['type' => 'string'])
            ->addField('passphrase', ['type' => 'string'])
            ->addField('domain', ['type' => 'string']);
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
            ->requirePresence('object_type', 'create', __('An object type is required.'))
            ->notEmptyString('object_type', __('The object type should not be empty.'))
            ->add('object_type', 'equals', [
                'last' => true,
                'rule' => [$this, 'checkIsValidType'],
                'message' => __('The object type is invalid.'),
            ]);

        $validator
            ->requirePresence('passphrase', 'create', __('A passphrase is required.'))
            ->allowEmptyString('passphrase', __('A passphrase is required.'))
            ->utf8Extended('passphrase', __('The passphrase should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->maxLength('fingerprint', 51, __('A fingerprint should not be greater than 51 characters.'))
            ->notEmptyString('fingerprint', __('A fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'custom', new IsValidFingerprintValidationRule());

        $validator
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->utf8('armored_key', __('The armored key should be a valid BMP-UTF8 string.'))
            ->add('armored_key', 'isPrivateKey', [
                'rule' => [$this, 'checkIsPrivateKey'],
                'message' => __('The value is not a valid OpenPGP private key.'),
            ])
            ->add('armored_key', 'matchPrivateFingerprints', [
                'last' => true,
                'rule' => [$this, 'checkPrivateFingerprint'],
                'message' => __(
                    'The fingerprint does not match the OpenPGP private keys fingerprint.'
                ),
            ]);

        $validator
            ->requirePresence('domain', 'create', __('A domain is required.'))
            ->notEmptyString('domain', __('The domain should not be empty.'));

        $sslForce = Configure::read('passbolt.ssl.force') ?? true;
        $validator->add('domain', 'urlWithProtocol', [
            'rule' => function ($value, $context) use ($sslForce) {
                if (strpos($value, 'https://') === 0) {
                    return true;
                }
                if ($sslForce) {
                    return false;
                }

                return strpos($value, 'http://') === 0;
            },
        ]);

        if (Configure::read('passbolt.security.checkDomainMismatch')) {
            $validator
                ->add('domain', 'equals', [
                    'last' => true,
                    'rule' => [$this, 'checkIsValidDomain'],
                    'message' => __('The domain should match current instance URL.'),
                ]);
        }

        return $validator;
    }

    /**
     * Check true if object type equals PASSBOLT_METADATA_PRIVATE_KEY
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsValidDomain(string $check, array $context): bool
    {
        return rtrim($check, '/') === rtrim(Router::url('/', true), '/');
    }

    /**
     * Check true if object type equals PASSBOLT_METADATA_PRIVATE_KEY
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsValidType(string $check, array $context): bool
    {
        return $check === self::PASSBOLT_METADATA_PRIVATE_KEY;
    }

    /**
     * Check true if field is a valid OpenPGP private key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsPrivateKey(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        if (!$gpg->isParsableArmoredPrivateKey($check)) {
            return false;
        }
        try {
            $gpg->getKeyInfo($check);
        } catch (CakeException $e) {
            return false;
        }

        return true;
    }

    /**
     * Check that the fingerprint given in parameter match the fingerprint of the private key.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkPrivateFingerprint(string $check, array $context): bool
    {
        if (!isset($context['data']['fingerprint']) || !is_string($context['data']['fingerprint'])) {
            return false;
        }
        $gpg = OpenPGPBackendFactory::get();
        $privateKeyArmored = Hash::get($context, 'data.armored_key');

        if ($privateKeyArmored === null) {
            return false;
        }
        $parsablePrivateKey = $gpg->isParsableArmoredPrivateKey($privateKeyArmored);
        if (!$parsablePrivateKey) {
            return false;
        }

        try {
            $privateKeyInfo = $gpg->getKeyInfo($privateKeyArmored);
            $privateKeyFingerprint = Hash::get($privateKeyInfo, 'fingerprint', '');
        } catch (Exception $e) {
            return false;
        }

        return $privateKeyFingerprint === $context['data']['fingerprint'];
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
            'object_type' => $data['object_type'] ?? null,
            'domain' => $data['domain'] ?? null,
            'armored_key' => $data['armored_key'] ?? null,
            'fingerprint' => $data['fingerprint'] ?? null,
            'passphrase' => $data['passphrase'] ?? null,
        ];
    }
}
