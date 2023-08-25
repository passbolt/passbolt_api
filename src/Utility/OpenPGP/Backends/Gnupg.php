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
 * @since         2.0.0
 */
namespace App\Utility\OpenPGP\Backends;

use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;

/**
 * Gpg wrapper utility
 *
 * This class provides tools for Gpg operations.
 * It is based on 2 different GPG libraries : Php Gnupg, and Openpgp-php
 *
 * We use 2 libraries instead of ones for the following reasons:
 *
 *  - bugs : Some operations of Php Gnupg have been seen to provoke segmentation fault in the past.
 *  Such segmentation faults happened when we try to import a key with an invalid format for instance.
 *  As a failsafe, since we still need to validate keys, we rely on Openpgp-php for pre-flying such operations
 *  and mitigate the associated security risks.
 *
 *  - Velocity : Openpgp-php is library implemented in PHP which is not as fast as Php Gnupg.
 *  So for all encryption and decryption operations, we will prefer Php Gnupg.
 */
class Gnupg extends OpenPGPBackend
{
    /**
     * Gpg object.
     *
     * @var \gnupg
     */
    protected $_gpg;

    /**
     * Constructor.
     *
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __construct()
    {
        parent::__construct();
        if (!extension_loaded('gnupg')) {
            throw new CakeException('PHP Gnupg library is not installed.');
        }
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }

        $this->_gpg = new \gnupg();
        $this->_gpg->seterrormode(GNUPG_ERROR_EXCEPTION);
    }

    /**
     * Set a key for encryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @throws \Cake\Core\Exception\CakeException if the key cannot be used to encrypt
     * @return bool true if success
     */
    public function setEncryptKey(string $armoredKey): bool
    {
        $this->_encryptKeyFingerprint = null;
        // Get the key info.
        $encryptKeyInfo = $this->getPublicKeyInfo($armoredKey);
        $fingerprint = $encryptKeyInfo['fingerprint'];

        try {
            $this->_gpg->addencryptkey($fingerprint);
            $this->_encryptKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            // It didn't work, maybe only key is not in the keyring
            // we import the key and retry
            $this->importKeyIntoKeyring($armoredKey);
            try {
                $this->_gpg->addencryptkey($fingerprint);
                $this->_encryptKeyFingerprint = $fingerprint;
            } catch (\Exception $e) {
                $msg = __('The key {0} cannot be used to encrypt.', $fingerprint) . ' ' . $e->getMessage();
                throw new CakeException($msg, null, $e);
            }
        }

        return true;
    }

    /**
     * Set a key for encryption.
     *
     * @param string $fingerprint fingerprint
     * @throws \Cake\Core\Exception\CakeException if key is not present in keyring or there was an issue to use the key to encrypt
     * @return bool true if success
     */
    public function setEncryptKeyFromFingerprint(string $fingerprint): bool
    {
        $this->_encryptKeyFingerprint = null;
        $this->assertKeyInKeyring($fingerprint);
        try {
            $this->_gpg->addencryptkey($fingerprint);
            $this->_encryptKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            $msg = __('The key {0} cannot be used to encrypt.', $fingerprint) . ' ' . $e->getMessage();
            throw new CakeException($msg, null, $e);
        }

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase to decrypt secret key
     * @throws \Cake\Core\Exception\CakeException if the key cannot be found in the keyring
     * @throws \Cake\Core\Exception\CakeException if the key is using a passphrase
     * @throws \Cake\Core\Exception\CakeException if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKey(string $armoredKey, string $passphrase): bool
    {
        $this->_decryptKeyFingerprint = null;

        // Get the key info.
        $decryptKeyInfo = $this->getKeyInfo($armoredKey);
        $fingerprint = $decryptKeyInfo['fingerprint'];

        try {
            $this->_gpg->adddecryptkey($fingerprint, $passphrase);
            $this->_decryptKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            // It didn't work, maybe only key is not in the keyring
            // we import the key and retry
            $this->importKeyIntoKeyring($armoredKey);
            try {
                $this->_gpg->adddecryptkey($fingerprint, $passphrase);
                $this->_decryptKeyFingerprint = $fingerprint;
            } catch (\Exception $e) {
                $msg = __('The key {0} cannot be used to decrypt.', $fingerprint);
                throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
            }
        }

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $fingerprint fingerprint of a key in the keyring
     * @param string $passphrase to decrypt secret key
     * @throws \Cake\Core\Exception\CakeException if the key cannot be found in the keyring
     * @throws \Cake\Core\Exception\CakeException if the key is using a passphrase
     * @throws \Cake\Core\Exception\CakeException if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKeyFromFingerprint(string $fingerprint, string $passphrase): bool
    {
        $this->_decryptKeyFingerprint = null;

        try {
            $this->_gpg->adddecryptkey($fingerprint, $passphrase);
            $this->_decryptKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            $msg = __('The key {0} cannot be used to decrypt.', $fingerprint) . ' ' . $e->getMessage();
            throw new CakeException($msg, null, $e);
        }

        return true;
    }

    /**
     * Set a key for signing.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase passphrase
     * @throws \Cake\Core\Exception\CakeException if the key is not already in the keyring
     * @throws \Cake\Core\Exception\CakeException if the passphrase is not empty
     * @throws \Cake\Core\Exception\CakeException if the key cannot be used for signing
     * @return bool
     */
    public function setSignKey(string $armoredKey, string $passphrase): bool
    {
        $this->_signKeyFingerprint = null;

        $signKeyInfo = $this->getKeyInfo($armoredKey);
        $fingerprint = $signKeyInfo['fingerprint'];

        try {
            // The key is in the keyring try to use it as a sign key
            $this->_gpg->addsignkey($fingerprint, $passphrase);
            $this->_signKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            // It didn't work, maybe only public key was in the keyring
            // we try to re-import the key
            $this->importKeyIntoKeyring($armoredKey);
            try {
                $this->_gpg->addsignkey($fingerprint, $passphrase);
                $this->_signKeyFingerprint = $fingerprint;
            } catch (\Exception $e) {
                $msg = __('Could not use key {0} for signing.', $fingerprint);
                throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
            }
        }

        return true;
    }

    /**
     * Set key to be used for signing
     *
     * @throws \Cake\Core\Exception\CakeException if the key is not already in the keyring
     * @throws \Cake\Core\Exception\CakeException if the passphrase is not empty
     * @throws \Cake\Core\Exception\CakeException if the key cannot be used for signing
     * @param string $fingerprint fingerprint
     * @param string $passphrase passphrase
     * @return true if success
     */
    public function setSignKeyFromFingerprint(string $fingerprint, string $passphrase): bool
    {
        $this->_signKeyFingerprint = null;

        try {
            $this->_gpg->addsignkey($fingerprint, $passphrase);
            $this->_signKeyFingerprint = $fingerprint;
        } catch (\Exception $e) {
            $msg = __('Could not use key {0} for signing.', $fingerprint);
            throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
        }

        return true;
    }

    /**
     * Get public key information.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws \Cake\Core\Exception\CakeException if the armored key cannot be parsed
     * @return array key information (see OpenPGPBackendInterface::getKeyInfo)
     */
    public function getPublicKeyInfo(string $armoredKey): array
    {
        if (
            $this->isParsableArmoredPublicKey($armoredKey) === false
            && $this->isParsableArmoredPrivateKey($armoredKey) === false
        ) {
            throw new CakeException(__('Could not parse the OpenPGP public key.'));
        }

        return $this->getKeyInfo($armoredKey);
    }

    /**
     * Is key currently in keyring
     *
     * @param string $fingerprint fingerprint
     * @return bool true if in keyring false otherwise
     */
    public function isKeyInKeyring(string $fingerprint): bool
    {
        try {
            $results = $this->_gpg->keyinfo($fingerprint);
        } catch (\Exception $e) {
            return false;
        }
        if (empty($results)) {
            return false;
        }

        return true;
    }

    /**
     * Import a key into the local keyring.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws \Cake\Core\Exception\CakeException if the key could not be imported
     * @return string key fingerprint
     */
    public function importKeyIntoKeyring(string $armoredKey): string
    {
        $msg = __('Could not import the OpenPGP key.');
        try {
            $import = $this->_gpg->import($armoredKey);
        } catch (\Exception $e) {
            throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
        }
        if (!is_array($import)) {
            throw new CakeException($msg);
        }
        if (!isset($import['fingerprint'])) {
            throw new CakeException($msg);
        }

        return $import['fingerprint'];
    }

    /**
     * Encrypt a text and optionally sign it too
     * Do not forget to add a key to encrypt and optionally to sign
     *
     * @param string $text plain text to be encrypted.
     * @param bool $sign whether the encrypted message should be signed.
     * @throws \Cake\Core\Exception\CakeException if no key was set to encrypt and optionally to sign
     * @throws \Cake\Core\Exception\CakeException if there is an issue with the key to encrypt and optionally to sign
     * @return string encrypted text
     */
    public function encrypt(string $text, bool $sign = false): string
    {
        $this->assertEncryptKey();
        if ($sign === true) {
            $msg = __('Could not use the key to sign and encrypt.');
            $this->assertSignKey();
            try {
                /** @var string|false $encryptedText */
                $encryptedText = $this->_gpg->encryptsign($text);
            } catch (\Exception $e) {
                throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
            }
            if ($encryptedText === false) {
                throw new CakeException($msg);
            }
            $this->clearSignKeys();
        } else {
            $msg = __('Could not use the key to encrypt.');
            $this->assertEncryptKey();
            try {
                /** @var string|false $encryptedText */
                $encryptedText = $this->_gpg->encrypt($text);
            } catch (\Exception $e) {
                throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
            }
            if ($encryptedText === false) {
                throw new CakeException($msg);
            }
        }
        $this->clearEncryptKeys();

        return $encryptedText;
    }

    /**
     * Encrypt a text and sign it too
     * Do not forget to add a key to encrypt and sign
     *
     * @param string $text plain text to be encrypted.
     * @throws \Cake\Core\Exception\CakeException if no key was set to encrypt and optionally to sign
     * @throws \Cake\Core\Exception\CakeException if there is an issue with the key to encrypt and optionally to sign
     * @return string encrypted text
     */
    public function encryptSign(string $text): string
    {
        return $this->encrypt($text, true);
    }

    /**
     * Decrypt a text
     *
     * @param string $text ASCII armored encrypted text to be decrypted.
     * @param bool $verifySignature should signature be verified
     * @throws \Cake\Core\Exception\CakeException if decryption fails
     * @return string decrypted text
     */
    public function decrypt(string $text, bool $verifySignature = false): string
    {
        $decrypted = false;
        $fingerprint = null;
        $signatureInfo = null;
        $this->assertDecryptKey();
        if ($verifySignature) {
            $this->assertVerifyKey();
            $fingerprint = $this->_verifyKeyFingerprint;
            $this->clearVerifyKeys();
        }
        try {
            if ($verifySignature === false) {
                $decrypted = $this->_gpg->decrypt($text);
            } else {
                /** @psalm-suppress InvalidArgument  */
                $signatureInfo = $this->_gpg->decryptverify($text, $decrypted);
            }
        } catch (\Exception $e) {
            $this->clearDecryptKeys();
            throw new CakeException(__('Decryption failed.') . ' ' . $e->getMessage(), null, $e);
        }
        $this->clearDecryptKeys();

        if ($decrypted === false) {
            throw new CakeException(__('Decryption failed.'));
        }
        if ($verifySignature) {
            if (empty($signatureInfo) || $signatureInfo[0]['fingerprint'] !== $fingerprint) {
                $msg = __('Expected {0} and got {1}.', $fingerprint, $signatureInfo[0]['fingerprint']);
                $msg = __('Decryption failed. Invalid signature.') . ' ' . $msg;
                throw new CakeException($msg);
            }
        }

        return $decrypted;
    }

    /**
     * Verify a signed message.
     *
     * @param string $signedText The signed message to verify.
     * @param string|null $plainText (optional) if this parameter is passed, it will be filled with the plain text.
     * @return array signature information
     * @throws \Cake\Core\Exception\CakeException If the armored signed message cannot be verified.
     */
    public function verify(string $signedText, ?string &$plainText = null): array
    {
        $this->assertVerifyKey();
        $msg = __('The message cannot be verified.');
        try {
            /** @psalm-suppress InvalidArgument */
            $signature = $this->_gpg->verify($signedText, false, $plainText);
            if (empty($signature) || $signature[0]['fingerprint'] !== $this->_verifyKeyFingerprint) {
                throw new CakeException($msg);
            }

            return $signature;
        } catch (\Exception $e) {
            throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
        }
    }

    /**
     * Sign a text.
     *
     * @param string $text plain text to be signed.
     * @throws \Cake\Core\Exception\CakeException if no key was set to sign
     * @throws \Cake\Core\Exception\CakeException if there is an issue with the key to sign
     * @return string signed text
     */
    public function sign(string $text): string
    {
        $msg = __('Could not sign the text. ');
        $this->assertSignKey();
        try {
            /** @var string|false $signedText */
            $signedText = $this->_gpg->sign($text);
            $this->clearSignKeys();
        } catch (\Exception $e) {
            $this->clearSignKeys();
            throw new CakeException($msg . ' ' . $e->getMessage(), null, $e);
        }
        if ($signedText === false) {
            throw new CakeException($msg);
        }

        return $signedText;
    }

    /**
     * Removes all keys which were set for decryption before
     *
     * @return void
     */
    public function clearDecryptKeys(): void
    {
        $this->_decryptKeyFingerprint = null;
        $this->_gpg->cleardecryptkeys();
    }

    /**
     * Removes all keys which were set for signing before
     *
     * @return void
     */
    public function clearSignKeys(): void
    {
        $this->_signKeyFingerprint = null;
        $this->_gpg->clearsignkeys();
    }

    /**
     * Removes all keys which were set for encryption before
     *
     * @return void
     */
    public function clearEncryptKeys(): void
    {
        $this->_encryptKeyFingerprint = null;
        $this->_gpg->clearencryptkeys();
    }

    /**
     * Delete a key identified with a fingerprint
     *
     * @param string $fingerprint fingerprint
     * @return bool returns true on success or false on failure.
     */
    public function deleteKey(string $fingerprint): bool
    {
        try {
            /** @psalm-suppress TooFewArguments false positive  */
            return $this->_gpg->deletekey($fingerprint); // @phpstan-ignore-line implemented in v0.5
        } catch (\Exception $exception) {
            return false;
        }
    }
}
