<?php
namespace App\Utility\OpenPGP;

use Cake\Core\Exception\Exception;

interface OpenPGPBackendInterface
{
    /**
     * OpenPGP ASCII armored message/key marker
     */
    const MESSAGE_MARKER = 'PGP MESSAGE';
    const PUBLIC_KEY_MARKER = 'PGP PUBLIC KEY BLOCK';
    const PRIVATE_KEY_MARKER = 'PGP PRIVATE KEY BLOCK';
    const SIGNED_MESSAGE_MARKER = 'PGP SIGNED MESSAGE';

    /**
     * Set a key for encryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @throws Exception if the key cannot be used to encrypt
     * @return bool true if success
     */
    public function setEncryptKey(string $armoredKey);

    /**
     * Set a key for encryption.
     *
     * @param string $fingerprint fingerprint
     * @throws Exception if key is not present in keyring
     * @throws Exception if there was an issue to use the key to encrypt
     * @return bool true if success
     */
    public function setEncryptKeyFromFingerprint(string $fingerprint);

    /**
     * Set a key for decryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase to decrypt secret key
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key is using a passphrase
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKey(string $armoredKey, string $passphrase);

    /**
     * Set a key for decryption.
     *
     * @param string $fingerprint fingerprint of a key in the keyring
     * @param string $passphrase to decrypt secret key
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key is using a passphrase
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKeyFromFingerprint(string $fingerprint, string $passphrase);

    /**
     * Set a key for verification (in decrypt or verify operation).
     *
     * @param string $fingerprint fingerprint of a key in the keyring
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key is using a passphrase
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setVerifyKeyFromFingerprint(string $fingerprint);

    /**
     * Set a key for signing.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase passphrase
     * @throws Exception if the key is not already in the keyring
     * @throws Exception if the passphrase is not empty
     * @throws Exception if the key cannot be used for signing
     * @return bool
     * @throws Exception
     */
    public function setSignKey(string $armoredKey, string $passphrase);

    /**
     * Set key to be used for signing
     *
     * @throws Exception if the key is not already in the keyring
     * @throws Exception if the passphrase is not empty
     * @throws Exception if the key cannot be used for signing
     * @param string $fingerprint fingerprint
     * @param string $passphrase passphrase
     * @return true if success
     */
    public function setSignKeyFromFingerprint(string $fingerprint, string $passphrase);

    /**
     * Import a key into the local keyring.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws Exception if the key could not be imported
     * @return string key fingerprint
     */
    public function importKeyIntoKeyring(string $armoredKey);

    /**
     * Check if an ASCII armored private key is parsable
     *
     * @param  string $armoredKey ASCII armored key data
     * @return bool true if parsable false otherwise
     */
    public function isParsableArmoredPublicKey(string $armoredKey);

    /**
     * Check if an ASCII armored private key is parsable
     *
     * @param  string $armoredKey ASCII armored key data
     * @return bool true if parsable false otherwise
     */
    public function isParsableArmoredPrivateKey(string $armoredKey);

    /**
     * Check if a message is valid.
     *
     * @param string $armored ASCII armored message data
     * @return bool true if valid, false otherwise
     */
    public function isValidMessage(string $armored);

    /**
     * Get key information
     *
     * Extract the information from the key and return them in an array:
     *  - fingerprint   : fingerprint of the key, string(40)
     *  - bits          : size / number of bits (int)
     *  - type          : algorithm used by the key (RSA, ELGAMAL, DSA, etc..)
     *  - key_id        : key id, string(8)
     *  - key_created   : date of creation of the key, timestamp
     *  - uid           : user id of the key following gpg standard (usually name surname (comment) <email>), string
     *  - expires       : expiration date or empty if no expiration date, timestamp
     *
     * Important note : this function is using OpenPgp-PHP library instead of php-gnupg to pre-validate the key.
     *
     * @param string $armoredKey the ASCII armored key block
     * @return array as described above
     */
    public function getKeyInfo(string $armoredKey);

    /**
     * Get public key information.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws Exception if the armored key cannot be parsed
     * @return array key information (see getKeyInfo)
     */
    public function getPublicKeyInfo(string $armoredKey);

    /**
     * Is key currently in keyring
     *
     * @param string $fingerprint fingerprint
     * @return bool true if in keyring false otherwise
     */
    public function isKeyInKeyring(string $fingerprint);

    /**
     * Encrypt a text and optionally sign it too
     * Do not forget to add a key to encrypt and optionally to sign
     *
     * @param string $text plain text to be encrypted.
     * @param bool $sign whether the encrypted message should be signed.
     * @throws Exception if no key was set to encrypt and optionally to sign
     * @throws Exception if there is an issue with the key to encrypt and optionally to sign
     * @return string encrypted text
     */
    public function encrypt(string $text, bool $sign = false);

    /**
     * Decrypt a text.
     *
     * @param string $text ASCII armored encrypted text to be decrypted.
     * @param bool $verifySignature should signature be verified
     * @throws Exception
     * @return string decrypted text
     */
    public function decrypt(string $text, bool $verifySignature = false);

    /**
     * Verify a signed message.
     *
     * @param string $armored The armored signed message to verify.
     * @param string $fingerprint The fingerprint of the key to verify for.
     * @param mixed $plainText (optional) The plain text.
     * @return void
     * @throws Exception If the armored signed message cannot be verified.
     */
    public function verify($armored, $fingerprint, &$plainText = null);

    /**
     * Sign a text.
     *
     * @param string $text plain text to be signed.
     * @throws Exception if no key was set to sign
     * @throws Exception if there is an issue with the key to sign
     * @return string signed text
     */
    public function sign(string $text);

    /**
     * Removes all keys which were set for decryption before
     *
     * @return void
     */
    public function clearDecryptKeys();

    /**
     * Removes all keys which were set for signing before
     *
     * @return void
     */
    public function clearSignKeys();

    /**
     * Removes all keys which were set for encryption before
     *
     * @return void
     */
    public function clearEncryptKeys();

    /**
     * Removes all keys which were set before
     *
     * @return void
     */
    public function clearKeys();
}
