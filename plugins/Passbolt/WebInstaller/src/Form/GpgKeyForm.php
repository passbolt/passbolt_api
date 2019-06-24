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
 * @since         2.7.0
 */
namespace Passbolt\WebInstaller\Form;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;

use Cake\Validation\Validator;

class GpgKeyForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('public_key_armored', 'string')
            ->addField('private_key_armored', ['type' => 'string'])
            ->addField('fingerprint', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('public_key_armored', 'create', __('A public key is required.'))
            ->notEmpty('public_key_armored', __('A public key is required.'))
            ->ascii('public_key_armored', __('The public key is not a valid ascii string.'))
            ->add('public_key_armored', 'is_public_key', [
                'rule' => [$this, 'checkIsPublicKey'],
                'message' => 'The key is not a valid public key'
            ])
            ->add('public_key_armored', 'has_no_expiry', [
                'rule' => [$this, 'checkHasNoExpiry'],
                'message' => 'The key cannot have an expiry date'
            ])
            ->add('public_key_armored', 'can_encrypt', [
                'last' => true,
                'rule' => [$this, 'checkCanEncrypt'],
                'message' => 'The public key cannot be used to encrypt.'
            ]);

        $validator
            ->requirePresence('private_key_armored', 'create', __('A private key is required.'))
            ->notEmpty('private_key_armored', __('A private key is required.'))
            ->ascii('private_key_armored', __('The private key is not a valid ascii string.'))
            ->add('private_key_armored', 'is_private_key', [
                'rule' => [$this, 'checkIsPrivateKey'],
                'message' => 'The key is not a valid private key'
            ])
            ->add('private_key_armored', 'has_no_expiry', [
                'rule' => [$this, 'checkHasNoExpiry'],
                'message' => 'The key cannot have an expiry date'
            ])
            ->add('private_key_armored', 'can_decrypt', [
                'last' => true,
                'rule' => [$this, 'checkCanDecrypt'],
                'message' => 'The private key cannot be used to decrypt. Please note that passbolt does not support GPG key protected with a secret.'
            ]);

        $validator
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->notEmpty('fingerprint', __('A fingerprint is required.'))
            ->alphaNumeric('fingerprint', __('The fingerprint is not a valid ascii string.'))
            ->add('fingerprint', 'match_public_private_fingerprints', [
                'last' => true,
                'rule' => [$this, 'checkPublicPrivateFingerprints'],
                'message' => 'The fingerprint does not match the public and the private keys fingerprints.'
            ]);

        return $validator;
    }

    /**
     * Check true if field is a valid private gpg key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsPublicKey($check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();
        if (!$gpg->isParsableArmoredPublicKey($check)) {
            return false;
        }
        try {
            $gpg->getKeyInfo($check);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Check true if field is a valid private gpg key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkIsPrivateKey($check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();
        if (!$gpg->isParsableArmoredPrivateKey($check)) {
            return false;
        }
        try {
            $gpg->getKeyInfo($check);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Check true if field is a valid private gpg key
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkHasNoExpiry($check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();
        try {
            $keyInfo = $gpg->getKeyInfo($check);
        } catch (Exception $e) {
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
    public function checkCanEncrypt($check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();
        try {
            $messageToEncrypt = 'open source password manager for teams';
            $fingerprint = $gpg->importKeyIntoKeyring($check);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $encryptedMessage = $gpg->encrypt($messageToEncrypt);
        } catch (Exception $e) {
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
    public function checkCanDecrypt($check, array $context)
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
        } catch (Exception $e) {
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
    public function checkPublicPrivateFingerprints($check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();
        $privateKeyArmored = Hash::get($context, 'data.private_key_armored');
        $publicKeyArmored = Hash::get($context, 'data.public_key_armored');

        if ($privateKeyArmored === null || $publicKeyArmored === null) {
            return false;
        }
        if (!$gpg->isParsableArmoredPrivateKey($privateKeyArmored) || !$gpg->isParsableArmoredPublicKey($publicKeyArmored)) {
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

        return ($publicKeyFingerprint == $check) && ($privateKeyFingerprint == $check);
    }

    /**
     * Execute.
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
