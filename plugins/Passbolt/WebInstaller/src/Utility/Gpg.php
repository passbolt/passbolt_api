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
namespace Passbolt\WebInstaller\Utility;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Utility\Hash;

class Gpg
{
    /**
     * Generate a key pair using system GPG binary V1.
     * @param array $keyData key data as provided by form
     * @throws Exception if the key cannot be generated
     * @return string key fingerprint
     */
    public static function generateKey($keyData)
    {
        $version = self::getGpgVersion();
        $isV2 = version_compare($version, '2.0.0', '>=');
        if ($isV2) {
            $generateKeyCmd = self::generateKeyCmdV2($keyData);
        } else {
            $generateKeyCmd = self::generateKeyCmdV1($keyData);
        }

        $cmdOutput = "";
        exec($generateKeyCmd, $cmdOutput, $cmdRes);

        if ($cmdRes !== 0) {
            throw new Exception("Could not generate GPG key");
        }

        $res = gnupg_init();
        $info = gnupg_keyinfo($res, $keyData['email']);

        if (empty($info) || !isset($info[0]['subkeys'][0]['fingerprint'])) {
            var_dump(__LINE__);
            throw new Exception("Could not retrieve the generated key");
        }

        // There can be several keys that match the email id (already present in the keyring before).
        // We need to identify the last one.
        $correspondingFingerprints = Hash::combine($info, '{n}.subkeys.0.timestamp', '{n}.subkeys.0.fingerprint');
        krsort($correspondingFingerprints);
        $lastGeneratedFingerprint = reset($correspondingFingerprints);

        return $lastGeneratedFingerprint;
    }

    /**
     * Export the public armored key.
     * @param string $fingerprint key fingerprint
     * @param string $path path to export the key in
     * @throws Exception The key cannot be exported
     * @return void
     */
    public static function exportPublicArmoredKey($fingerprint, $path)
    {
        $cmd = "gpg --armor --export $fingerprint > $path";
        exec($cmd, $cmdOutput, $cmdRes);
        if ($cmdRes !== 0) {
            throw new Exception("Could not export public key");
        }
    }

    /**
     * Export the private armored key.
     * @param string $fingerprint key fingerprint
     * @param string $path path to export the key in
     * @throws Exception when the key cannot be exported
     * @return void
     */
    public static function exportPrivateArmoredKey($fingerprint, $path)
    {
        $cmd = "gpg --armor --export-secret-keys $fingerprint > $path";
        exec($cmd, $cmdOutput, $cmdRes);
        if ($cmdRes !== 0) {
            throw new Exception("Could not export private key");
        }
    }

    /**
     * Get server GPG version.
     * @return mixed
     */
    public static function getGpgVersion()
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
    protected static function generateKeyCmdV2($keyData)
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
    protected static function generateKeyCmdV1($keyData)
    {
        $cmd = "gpg --batch --no-tty --gen-key <<EOF
Key-Type: 1
Key-Length: 2048
Name-Real: {$keyData['name']}" . (isset($keyData['comment']) && !empty($keyData['comment']) ? "
Name-Comment: {$keyData['comment']}" : '') . "
Name-Email: {$keyData['email']}
Expire-Date: 0
EOF";

        return $cmd;
    }
}
