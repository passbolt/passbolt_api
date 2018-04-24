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

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;

use Cake\Validation\Validator;

class GpgKeyGenerateForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('name', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('comment', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('name', 'create', __('A key name is required.'))
            ->notEmpty('name', __('A key name is required.'))
            ->utf8('name', __('The key name is not a valid utf8 string.'));

        $validator
            ->requirePresence('email', 'create', __('A key email is required.'))
            ->notEmpty('email', __('A key email is required.'))
            ->utf8('email', __('The key email is not a valid utf8 string.'))
            ->email('email', Configure::read('passbolt.email.validate.mx'), __('The key email is not a valid email address'));

        $validator
            ->allowEmpty('comment')
            ->utf8('comment', __('The key comment is not a valid utf8 string.'));

        return $validator;
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

    /**
     * Export armored keys in the config folder based on the fingerprint provided.
     * @param string $fingerprint key fingerprint
     * @throws Exception when the key cannot be exported
     * @return void
     */
    public function exportArmoredKeys($fingerprint)
    {
        $publicKeyPath = Configure::read('passbolt.gpg.serverKey.public');
        $privateKeyPath = Configure::read('passbolt.gpg.serverKey.private');

        $cmd = "gpg --armor --export $fingerprint > $publicKeyPath";
        exec($cmd, $cmdOutput, $cmdRes);
        if ($cmdRes !== 0) {
            throw new Exception("Could not export public key");
        }

        $cmd = "gpg --armor --export-secret-keys $fingerprint > $privateKeyPath";
        exec($cmd, $cmdOutput, $cmdRes);
        if ($cmdRes !== 0) {
            throw new Exception("Could not export private key");
        }
    }

    /**
     * Get server GPG version.
     * @return mixed
     */
    public function getGpgVersion()
    {
        exec('gpg --version', $response, $res);
        preg_match("/((?:[0-9]+\.?)+)/i", $response[0], $matches);

        return $matches[0];
    }

    /**
     * Generate a key pair using system GPG binary V2.
     * @param array $keyData key data as provided by form
     * @return string command
     */
    public function generateKeyCmdV2($keyData)
    {
        $cmd = "gpg --batch --no-tty --gen-key <<EOF
Key-Type: default
Key-Length: 2048
Subkey-Type: default
Subkey-Length: 2048
Name-Real: {$keyData['name']}" . (isset($keyData['comment']) && !empty($keyData['comment']) ? "
Name-Comment: {$keyData['comment']}" : '') . "
Name-Email: {$keyData['email']}
Expire-Date: 0
%no-protection
%commit
EOF";

        return $cmd;
    }

    /**
     * Generate a key pair using system GPG binary V1.
     * @param array $keyData key data as provided by form
     * @return string command
     */
    public function generateKeyCmdV1($keyData)
    {
        $cmd = "gpg --batch --gen-key <<EOF
Key-Type: 1
Key-Length: 2048
Name-Real: {$keyData['name']}" . (isset($keyData['comment']) && !empty($keyData['comment']) ? "
Name-Comment: {$keyData['comment']}" : '') . "
Name-Email: {$keyData['email']}
Expire-Date: 0
EOF";

        return $cmd;
    }

    /**
     * Generate a key pair using system GPG binary V1.
     * @param array $keyData key data as provided by form
     * @throws Exception if the key cannot be generated
     * @return string key fingerprint
     */
    public function generateKey($keyData)
    {
        $version = $this->getGpgVersion();
        $isV2 = version_compare($version, '2.0.0', '>=');
        if ($isV2) {
            $generateKeyCmd = $this->generateKeyCmdV2($keyData);
        } else {
            $generateKeyCmd = $this->generateKeyCmdV1($keyData);
        }

        $cmdOutput = "";
        exec($generateKeyCmd, $cmdOutput, $cmdRes);

        if ($cmdRes !== 0) {
            throw new Exception("Could not generate GPG key");
        }

        $res = gnupg_init();
        $info = gnupg_keyinfo($res, $keyData['email']);

        if (empty($info) || !isset($info[0]['subkeys'][0]['fingerprint'])) {
            throw new Exception("Could not retrieve the generated key");
        }

        // There can be several keys that match the email id (already present in the keyring before).
        // We need to identify the last one.
        $correspondingFingerprints = Hash::combine($info, '{n}.subkeys.0.timestamp', '{n}.subkeys.0.fingerprint');
        krsort($correspondingFingerprints);
        $lastGeneratedFingerprint = reset($correspondingFingerprints);

        return $lastGeneratedFingerprint;
    }
}
