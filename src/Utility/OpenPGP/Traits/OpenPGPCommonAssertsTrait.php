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
 * @since         3.6.0
 */
declare(strict_types=1);

namespace App\Utility\OpenPGP\Traits;

use Cake\Core\Exception\CakeException;

trait OpenPGPCommonAssertsTrait
{
    /**
     * Assert an armored message/key marker is present in plaintext
     *
     * @param string $armoredText message or key in ASCII armored format
     * @param string $marker a message delimiter like 'PGP MESSAGE'
     * @throws \Cake\Core\Exception\CakeException if the armored message marker does not match the one provided
     * @return bool true if successful
     */
    public function assertGpgMarker(string $armoredText, string $marker)
    {
        $msg = __('This is not a valid OpenPGP armored marker.');
        try {
            $m = $this->getGpgMarker($armoredText);
        } catch (CakeException $e) {
            throw new CakeException($msg);
        }
        if ($m !== $marker) {
            throw new CakeException($msg);
        }

        return true;
    }

    /**
     * Assert key is in the keyring
     *
     * @param string $fingerprint fingerprint
     * @return void
     */
    public function assertKeyInKeyring(string $fingerprint)
    {
        if (!$this->isKeyInKeyring($fingerprint)) {
            throw new CakeException(__('The key {0} was not found in the keyring', $fingerprint));
        }
    }

    /**
     * Assert the signature key is set
     *
     * @throws \Cake\Core\Exception\CakeException if not signature key is set
     * @return void
     */
    public function assertSignKey()
    {
        if (empty($this->_signKeyFingerprint)) {
            throw new CakeException('Can not sign without a key. Set a sign key first.');
        }
    }

    /**
     * Assert the verification key is set
     *
     * @throws \Cake\Core\Exception\CakeException if not signature key is set
     * @return void
     */
    public function assertVerifyKey()
    {
        if (empty($this->_verifyKeyFingerprint)) {
            throw new CakeException('Can not verify without a key. Set a verification key first.');
        }
    }

    /**
     * Check if an encryption key is set
     *
     * @throws \Cake\Core\Exception\CakeException if no encryption key is set
     * @return void
     */
    public function assertEncryptKey()
    {
        if (empty($this->_encryptKeyFingerprint)) {
            throw new CakeException('Can not encrypt without a key. Set a public key first.');
        }
    }

    /**
     * Check if a decrypt key is set
     *
     * @throws \Cake\Core\Exception\CakeException if no decryption key is set
     * @return void
     */
    public function assertDecryptKey()
    {
        if (empty($this->_decryptKeyFingerprint)) {
            throw new CakeException('Can not decrypt without a key. Set a secret key first.');
        }
    }
}
