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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use App\Utility\Gpg;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class GpgKeyImportForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('armored_key', 'string');
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmpty('armored_key', __('An armored key is required.'))
            ->ascii('armored_key', __('The key is not a valid ascii string.'))
            ->add('armored_key', 'is_private_key', [
                'last' => true,
                'rule' => [$this, 'checkIsPrivateKey'],
                'message' => 'The key is not a valid private key'
            ])
            ->add('armored_key', 'has_no_expiry', [
                'last' => true,
                'rule' => [$this, 'checkHasNoExpiry'],
                'message' => 'The key cannot have an expiry date'
            ])
            ->add('armored_key', 'can_encrypt_decrypt', [
                'last' => true,
                'rule' => [$this, 'checkCanEncryptDecrypt'],
                'message' => 'The key cannot be used to encrypt/decrypt. Please note that passbolt does not support GPG key protected with a secret.'
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
    public function checkIsPrivateKey($check, array $context)
    {
        $gpg = new Gpg();
        if (!$gpg->isParsableArmoredPrivateKeyRule($check)) {
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
        $gpg = new Gpg();
        try {
            $keyInfo = $gpg->getKeyInfo($check);
        } catch (Exception $e) {
            return false;
        }

        if (!is_null($keyInfo['expires'])) {
            return false;
        }

        return true;
    }

    /**
     * Check that the key provided can be used to encrypt and decrypt.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function checkCanEncryptDecrypt($check, array $context)
    {
        $gpg = new Gpg();
        try {
            $messageToEncrypt = 'open source password manager for teams';
            $gpg->setEncryptKey($check);
            $gpg->setSignKey($check);
            $encryptedMessage = $gpg->encrypt($messageToEncrypt, true);
            $gpg->setDecryptKey($check);
            $decryptedMessage = $gpg->decrypt($encryptedMessage, '', true);
        } catch (Exception $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }

        if ($messageToEncrypt !== $decryptedMessage) {
            return false;
        }

        return true;
    }

    /**
     * Execute implementation.
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
