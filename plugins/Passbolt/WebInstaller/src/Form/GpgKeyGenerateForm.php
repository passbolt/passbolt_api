<?php
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
     * @param Schema $schema
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
     * @param Validator $validator
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
            ->email('email', __('The key email is not a valid email address'));

        $validator
            ->allowEmpty('comment')
            ->utf8('comment', __('The key comment is not a valid utf8 string.'));

        return $validator;
    }

    /**
     * Execute implementation.
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }

    /**
     * Export armored keys in the config folder based on the fingerprint provided.
     * @param $fingerprint
     */
    public function exportArmoredKeys($fingerprint) {
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
    public function getGpgVersion() {
	    exec('gpg --version', $response, $res);
	    preg_match("/((?:[0-9]+\.?)+)/i", $response[0], $matches);
	    return $matches[0];
    }

	/**
	 * Generate a key pair using system GPG binary V2.
	 * @param $keyData
	 * @return string
	 */
	public function generateKeyCmdV2($keyData) {
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
	 * @param $keyData
	 * @return string
	 */
	public function generateKeyCmdV1($keyData) {
		$cmd = "gpg --batch --gen-key <<EOF
Key-Type: 1
Key-Length: 2048
Name-Real: {$keyData['name']}" . (isset($keyData['comment']) && !empty($keyData['comment']) ? "
Name-Comment: {$keyData['comment']}" : '') . "
Name-Email: {$keyData['email']}
Expire-Date: 0
EOF";

		echo $cmd; die();

		return $cmd;
	}

    /**
     * Generate a key pair using system GPG binary V1.
     * @param $keyData
     * @return string
     */
    public function generateKey($keyData) {
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

        if(empty($info) || !isset($info[0]['subkeys'][0]['fingerprint'])) {
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