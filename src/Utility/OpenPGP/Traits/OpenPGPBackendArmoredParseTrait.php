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
 * @since         3.6.0
 */
namespace App\Utility\OpenPGP\Traits;

use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use OpenPGP;
use OpenPGP_Message;
use OpenPGP_PublicKeyPacket;
use OpenPGP_SecretKeyPacket;

trait OpenPGPBackendArmoredParseTrait
{
    /**
     * Get the gpg marker.
     *
     * @param string $armored ASCII armored gpg data
     * @return mixed
     * @throws \Cake\Core\Exception\CakeException
     */
    protected function getGpgMarker(string $armored): mixed
    {
        $isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $armored, $values);
        if (!$isMarker || !isset($values[2])) {
            throw new CakeException(__('No OpenPGP marker found.'));
        }

        return $values[2];
    }

    /**
     * Forked from OpenPGP::unarmor
     * Fail if key doesn't contain CRC instead of triggering php error
     *
     * @param string $text key
     * @param string $header header
     * @return string|false
     */
    private function unarmor(string $text, string $header = 'PGP PUBLIC KEY BLOCK'): false|string
    {
        $header = OpenPGP::header($header);
        $text = str_replace(["\r\n", "\r"], ["\n", ''], $text);

        // The armored text must start with the header, e.g. "-----BEGIN PGP PUBLIC KEY BLOCK-----"
        $headerPos = strpos($text, $header);
        if ($headerPos === false) {
            return false;
        }

        // The base64 data starts after the empty line following the header.
        $emptyLinePos = strpos($text, "\n\n", $headerPos + strlen($header));
        if ($emptyLinePos === false) {
            return false;
        }
        $dataPos = $emptyLinePos + 2;

        // The base64 data ends where the CRC line starts, e.g. "=abcd".
        $crcPos = strpos($text, "\n=", $dataPos);
        if ($crcPos === false) {
            // no CRC, consider the key invalid
            return false;
        }

        return base64_decode(substr($text, $dataPos, $crcPos - $dataPos), true);
    }

    /**
     * Check if an ASCII armored public key is parsable
     *
     * To do this, we try to unarmor the key. If the operation is successful, then we consider that
     * the key is a valid one.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool true if valid, false otherwise
     */
    public function isParsableArmoredPublicKey(string $armoredKey): bool
    {
        try {
            $this->assertGpgMarker($armoredKey, self::PUBLIC_KEY_MARKER);
        } catch (CakeException $e) {
            return false;
        }

        // If we don't manage to unarmor the key, we consider it's not a valid one.
        $keyUnarmored = $this->unarmor($armoredKey, self::PUBLIC_KEY_MARKER);
        if ($keyUnarmored === false) {
            return false;
        }

        // Try to parse the key
        $publicKey = @OpenPGP_PublicKeyPacket::parse($keyUnarmored); // phpcs:ignore
        if (empty($publicKey) || empty($publicKey->fingerprint) || empty($publicKey->key)) {
            return false;
        }

        $isStrictParsing = Configure::read('passbolt.gpg.security.strictPublicKeyParsing');
        if (!$isStrictParsing) {
            return true;
        }

        // --- Strict checks on public keys --- //

        // Only the first armored block is parsed, but gnupg would import all of them.
        if (substr_count($armoredKey, '-----BEGIN PGP') > 1) {
            return false;
        }

        if ($this->containsSecretKeyMaterial($armoredKey)) {
            return false;
        }

        return true;
    }

    /**
     * Check if an ASCII armored key block contains a secret key or a secret subkey packet.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool true if a secret packet is found, false otherwise
     */
    public function containsSecretKeyMaterial(string $armoredKey): bool
    {
        try {
            $marker = $this->getGpgMarker($armoredKey);
        } catch (CakeException) {
            return false;
        }

        if (!is_string($marker)) {
            return false;
        }

        $keyUnarmored = $this->unarmor($armoredKey, $marker);
        if ($keyUnarmored === false) {
            return false;
        }

        $message = @OpenPGP_Message::parse($keyUnarmored); // phpcs:ignore
        if (!$message instanceof OpenPGP_Message) {
            return false;
        }

        // OpenPGP_SecretSubkeyPacket extends OpenPGP_SecretKeyPacket, so subkeys are caught too.
        foreach ($message->packets as $packet) {
            if ($packet instanceof OpenPGP_SecretKeyPacket) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if an ASCII armored private key is parsable
     *
     * To do this, we try to unarmor the key. If the operation is successful, then we consider that
     * the key is a valid one.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool true if parsable false otherwise
     */
    public function isParsableArmoredPrivateKey(string $armoredKey): bool
    {
        try {
            $this->assertGpgMarker($armoredKey, self::PRIVATE_KEY_MARKER);
        } catch (CakeException $e) {
            return false;
        }

        // If we don't manage to unarmor the key, we consider it's not a valid one.
        $keyUnarmored = $this->unarmor($armoredKey, self::PRIVATE_KEY_MARKER);
        if ($keyUnarmored === false) {
            return false;
        }

        // Try to parse the key
        $privateKey = @OpenPGP_SecretKeyPacket::parse($keyUnarmored); // phpcs:ignore
        if (empty($privateKey) || empty($privateKey->fingerprint) || empty($privateKey->key)) {
            return false;
        }

        return true;
    }

    /**
     * Check if an ASCII armored signed message is parsable
     *
     * @param string $armored ASCII armored signed message
     * @return bool
     */
    public function isParsableArmoredSignedMessage(string $armored): bool
    {
        try {
            $marker = $this->getGpgMarker($armored);
        } catch (CakeException $e) {
            return false;
        }
        if ($marker !== self::SIGNED_MESSAGE_MARKER) {
            return false;
        }

        return true;
    }
}
