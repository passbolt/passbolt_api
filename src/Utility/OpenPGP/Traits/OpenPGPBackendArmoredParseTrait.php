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

use Cake\Core\Exception\CakeException;

trait OpenPGPBackendArmoredParseTrait
{
    /**
     * Get the gpg marker.
     *
     * @param string $armored ASCII armored gpg data
     * @return mixed
     * @throws \Cake\Core\Exception\CakeException
     */
    protected function getGpgMarker(string $armored)
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
     * @return false|string
     */
    private function unarmor(string $text, string $header = 'PGP PUBLIC KEY BLOCK')
    {
        // @codingStandardsIgnoreStart
        $header = \OpenPGP::header($header);
        $text = str_replace(["\r\n", "\r"], ["\n", ''], $text);
        if (
            ($pos1 = strpos($text, $header)) !== false &&
            ($pos1 = strpos($text, "\n\n", $pos1 += strlen($header))) !== false
        ) {
            $pos2 = strpos($text, "\n=", $pos1 += 2);
            if ($pos2 === false) {
                // no CRC, consider the key invalid
                return false;
            }
            $text = substr($text, $pos1, $pos2 - $pos1);
            return base64_decode($text, true);
        }

        return false;
        // @codingStandardsIgnoreEnd
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
        // @codingStandardsIgnoreStart
        $publicKey = @(\OpenPGP_PublicKeyPacket::parse($keyUnarmored));
        // @codingStandardsIgnoreEnd
        if (empty($publicKey) || empty($publicKey->fingerprint) || empty($publicKey->key)) {
            return false;
        }

        return true;
    }

    /**
     * Check if an ASCII armored private key is parsable
     *
     * To do this, we try to unarmor the key. If the operation is successful, then we consider that
     * the key is a valid one.
     *
     * @param  string $armoredKey ASCII armored key data
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
        // @codingStandardsIgnoreStart
        $privateKey = @(\OpenPGP_SecretKeyPacket::parse($keyUnarmored));
        // @codingStandardsIgnoreEnd
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
