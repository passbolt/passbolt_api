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
 * @since         2.7.0
 */
namespace Passbolt\WebInstaller\Form;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Exception\CakeException;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

class GpgKeyForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('public_key_armored', 'string')
            ->addField('private_key_armored', ['type' => 'string'])
            ->addField('fingerprint', ['type' => 'string']);
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
            ->requirePresence('public_key_armored', 'create', __('An OpenPGP public key is required.'))
            ->notEmptyString('public_key_armored', __('The OpenPGP public key should not be empty.'))
            ->ascii('public_key_armored', __('The OpenPGP public key should be a valid ASCII string.'))
            ->add('public_key_armored', 'is_public_key', [
                'rule' => [$this, 'checkIsPublicKey'],
                'message' => __('The key is not a valid OpenPGP public key.'),
            ])
            ->add('public_key_armored', 'has_no_expiry', [
                'rule' => [$this, 'checkHasNoExpiry'],
                'message' => __('The OpenPGP public key should not have an expiry date.'),
            ])
            ->add('public_key_armored', 'can_encrypt', [
                'last' => true,
                'rule' => [$this, 'checkCanEncrypt'],
                'message' => __('The OpenPGP public key cannot be used to encrypt.'),
            ]);

        $validator
            ->requirePresence('private_key_armored', 'create', __('An OpenPGP private key is required.'))
            ->notEmptyString('private_key_armored', __('The OpenPGP private key should not be empty.'))
            ->ascii('private_key_armored', __('The OpenPGP private key should be a valid ASCII string.'))
            ->add('private_key_armored', 'is_private_key', [
                'rule' => [$this, 'checkIsPrivateKey'],
                'message' => __('The value is not a valid OpenPGP private key.'),
            ])
            ->add('private_key_armored', 'has_no_expiry', [
                'rule' => [$this, 'checkHasNoExpiry'],
                'message' => __('The OpenPGP private key should not have an expiry date.'),
            ])
            ->add('private_key_armored', 'can_decrypt', [
                'last' => true,
                'rule' => [$this, 'checkCanDecrypt'],
                'message' => __('The OpenPGP private key cannot be used to decrypt.') . ' ' .
                    __('Please note that passbolt does not support OpenPGP key protected with a secret.'),
            ]);

        $validator
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'match_public_private_fingerprints', [
                'last' => true,
                'rule' => [$this, 'checkPublicPrivateFingerprints'],
                'message' => __(
                    'The fingerprint does not match the OpenPGP public and the OpenPGP private keys fingerprints.'
                ),
            ]);

        return $validator;
    }

    /**
     * Check true if field is a valid OpenPGP private key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsPublicKey(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        if (!$gpg->isParsableArmoredPublicKey($check)) {
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
     * Check true if field is a valid OpenPGP private key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkHasNoExpiry(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        try {
            $keyInfo = $gpg->getKeyInfo($check);
        } catch (CakeException $e) {
            return false;
        }

        if (!empty($keyInfo['expires'])) {
            return false;
        }

        return true;
    }

    /**
     * Check that the key provided can be used to encrypt data.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkCanEncrypt(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        try {
            $messageToEncrypt = 'open source password manager for teams';
            $fingerprint = $gpg->importKeyIntoKeyring($check);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $encryptedMessage = $gpg->encrypt($messageToEncrypt);
        } catch (CakeException $e) {
            return false;
        }

        return true;
    }

    /**
     * Check that the key provided can be used to decrypt.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkCanDecrypt(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        try {
            $messageToEncrypt = 'open source password manager for teams';
            $fingerprint = $gpg->importKeyIntoKeyring($check);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setSignKeyFromFingerprint($fingerprint, '');
            $encryptedMessage = $gpg->encrypt($messageToEncrypt, true);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, '');
            $decryptedMessage = $gpg->decrypt($encryptedMessage, true);
        } catch (CakeException $e) {
            return false;
        }

        if ($messageToEncrypt !== $decryptedMessage) {
            return false;
        }

        return true;
    }

    /**
     * Check that the fingerprint given in parameter match the fingerprint of the public and the private keys.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkPublicPrivateFingerprints(string $check, array $context): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        $privateKeyArmored = Hash::get($context, 'data.private_key_armored');
        $publicKeyArmored = Hash::get($context, 'data.public_key_armored');

        if ($privateKeyArmored === null || $publicKeyArmored === null) {
            return false;
        }
        $parsablePrivateKey = $gpg->isParsableArmoredPrivateKey($privateKeyArmored);
        if (!$parsablePrivateKey || !$gpg->isParsableArmoredPublicKey($publicKeyArmored)) {
            return false;
        }

        try {
            $privateKeyInfo = $gpg->getKeyInfo($privateKeyArmored);
            $privateKeyFingerprint = Hash::get($privateKeyInfo, 'fingerprint', '');
            $publicKeyInfo = $gpg->getKeyInfo($publicKeyArmored);
            $publicKeyFingerprint = Hash::get($publicKeyInfo, 'fingerprint', '');
        } catch (\Exception $e) {
            return false;
        }

        return ($publicKeyFingerprint === $check) && ($privateKeyFingerprint === $check);
    }

    /**
     * Execute.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
