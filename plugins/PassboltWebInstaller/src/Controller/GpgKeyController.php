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
namespace PassboltWebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Controller\Component\FlashComponent;
use Cake\Utility\Hash;

class GpgKeyController extends Controller
{
    var $components = ['Flash'];

    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            // TODO: validate key details.
            try {
                $fingerprint = $this->_generateKey($this->request->getData());
                $this->_exportArmoredKeys($fingerprint);
            } catch (Exception $e) {
                $this->Flash->error($e->getMessage());
                return $this->render('Pages/gpg_key_generate');
            }

            $session = $this->request->getSession();
            $session->write('gpg', [
                'fingerprint' => $fingerprint,
            ]);

            return $this->redirect('install/emails');
        }

        $this->render('Pages/gpg_key_generate');
    }

    /**
     * Export armored keys in the config folder based on the fingerprint provided.
     * @param $fingerprint
     */
    private function _exportArmoredKeys($fingerprint) {
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
     * Generate a key pair using system GPG binary.
     * @param $keyData
     * @return string
     */
    private function _generateKey($keyData) {
        $keyBatchFileContent = "Key-Type: 1
Key-Length: 2048
Subkey-Type: 1
Subkey-Length: 2048
Name-Real: {$keyData['name']}" . (isset($keyData['comment']) && !empty($keyData['comment']) ? "
Name-Comment: {$keyData['comment']}" : '') . "
Name-Email: {$keyData['email']}
Expire-Date: 0";

        $fileName = 'key_' . rand(10000, 99999) . '.txt';
        $filePath = TMP . $fileName;
        file_put_contents($filePath, $keyBatchFileContent);

        $generateKeyCmd = "gpg --batch --gen-key $filePath";

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

        // Delete previously created key batch file.
        unlink($filePath);

        // There can be several keys that match the email id (already present in the keyring before).
        // We need to identify the last one.
        $correspondingFingerprints = Hash::combine($info, '{n}.subkeys.0.timestamp', '{n}.subkeys.0.fingerprint');
        krsort($correspondingFingerprints);
        $lastGeneratedFingerprint = reset($correspondingFingerprints);

        return $lastGeneratedFingerprint;
    }
}