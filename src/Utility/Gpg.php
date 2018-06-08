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
namespace App\Utility;

//use \Exception as Exception;
use Cake\Core\Exception\Exception;
use \OpenPGP as OpenPGP;
use \OpenPGP_Message as OpenPGP_Message;
use \OpenPGP_PublicKeyPacket as OpenPGP_PublicKeyPacket;

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
 *
 */
class Gpg
{

    /**
     * Encryption key.
     */
    public $encryptKey = null;

    /**
     * Encryption key info.
     */
    public $encryptKeyInfo = [];

    /**
     * Decryption key.
     */
    public $decryptKey = null;

    /**
     * Decryption key info.
     */
    public $decryptKeyInfo = [];

    /**
     * Signing key.
     */
    public $signKey = null;

    /**
     * Signing key info.
     */
    public $signKeyInfo = [];

    /**
     * Gpg object.
     */
    protected $_gpg = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        if (!extension_loaded('gnupg')) {
            throw new Exception('PHP Gnupg library is not installed.');
        }
        $this->_gpg = new \gnupg();
        $this->_gpg->seterrormode(\gnupg::ERROR_EXCEPTION);
    }

    /**
     * Set a key for encryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool
     * @throws Exception
     */
    public function setEncryptKey($armoredKey)
    {
        // Get the key info.
        $this->encryptKeyInfo = $this->getPublicKeyInfo($armoredKey);

        // Import key inside the keyring.
        $this->importKeyIntoKeyring($armoredKey);

        // Store armored key.
        $this->encryptKey = $armoredKey;

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool
     * @throws Exception
     */
    public function setDecryptKey($armoredKey)
    {
        // Get the key info.
        $this->decryptKeyInfo = $this->getKeyInfo($armoredKey);

        // Import key inside the keyring.
        $this->importKeyIntoKeyring($armoredKey);

        // Store armored key.
        $this->decryptKey = $armoredKey;

        return true;
    }

    /**
     * Set a key for signing.
     *
     * @param string $armoredKey ASCII armored key data
     * @return bool
     * @throws Exception
     */
    public function setSignKey($armoredKey)
    {
        // Get the key info.
        $this->signKeyInfo = $this->getKeyInfo($armoredKey);

        // Import key inside the keyring.
        $this->importKeyIntoKeyring($armoredKey);

        // Store armored key.
        $this->signKey = $armoredKey;

        return true;
    }

    /**
     * Get the gpg marker.
     *
     * @param string $armored ASCII armored gpg data
     * @return mixed
     * @throws Exception
     */
    public function getGpgMarker($armored)
    {
        $isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $armored, $values);
        if (!$isMarker || !isset($values[2])) {
            throw new Exception('No marker found.');
        }

        return $values[2];
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
    public function isParsableArmoredPublicKeyRule($armoredKey)
    {
        // First, we try to get the marker of the gpg message.
        try {
            $marker = $this->getGpgMarker($armoredKey);
        } catch (Exception $e) {
            // If we can't extract the marker, we consider it's not a valid key.
            return false;
        }

        // Do not accept secret keys or message as valid marker
        if ($marker !== 'PGP PUBLIC KEY BLOCK') {
            return false;
        }

        // Try to unarmor the key.
        $keyUnarmored = OpenPGP::unarmor($armoredKey, $marker);
        // If we don't manage to unarmor the key, we consider it's not a valid one.
        if ($keyUnarmored == false) {
            return false;
        }

        // Try to parse the key
        // @codingStandardsIgnoreStart
        $publicKey = @(\OpenPGP_PublicKeyPacket::parse($keyUnarmored));
        // @codingStandardsIgnoreEnd
        if (empty($publicKey)) {
            return false;
        } else {
            if (empty($publicKey->fingerprint) || empty($publicKey->key)) {
                return false;
            }
        }

        // All tests pass, return true.
        return true;
    }

    /**
     * Check if an ASCII armored private key is parsable
     *
     * To do this, we try to unarmor the key. If the operation is successful, then we consider that
     * the key is a valid one.
     *
     * @param  string $armoredKey ASCII armored key data
     * @return bool
     */
    public function isParsableArmoredPrivateKeyRule($armoredKey)
    {
        // First, we try to get the marker of the gpg message.
        try {
            $marker = $this->getGpgMarker($armoredKey);
        } catch (Exception $e) {
            // If we can't extract the marker, we consider it's not a valid key.
            return false;
        }

        // Do not accept secret keys or message as valid marker
        if ($marker !== 'PGP PRIVATE KEY BLOCK') {
            return false;
        }

        // Try to unarmor the key.
        $keyUnarmored = OpenPGP::unarmor($armoredKey, $marker);
        // If we don't manage to unarmor the key, we consider it's not a valid one.
        if ($keyUnarmored == false) {
            return false;
        }

        // Try to parse the key
        // @codingStandardsIgnoreStart
        $publicKey = @(\OpenPGP_SecretKeyPacket::parse($keyUnarmored));
        // @codingStandardsIgnoreEnd
        if (empty($publicKey)) {
            return false;
        } else {
            if (empty($publicKey->fingerprint) || empty($publicKey->key)) {
                return false;
            }
        }

        // All tests pass, return true.
        return true;
    }

    /**
     * Check if an ASCII armored signed message is parsable
     *
     * @param  string $armored ASCII armored signed message
     * @return bool
     */
    public function isParsableArmoredSignedMessageRule($armored)
    {
        // First, we try to get the marker of the gpg signed message.
        try {
            $marker = $this->getGpgMarker($armored);
        } catch (Exception $e) {
            // If we can't extract the marker, we consider it's not a valid signed message.
            return false;
        }

        // The marker is not the expected one
        if ($marker !== 'PGP SIGNED MESSAGE') {
            return false;
        }

        // All tests pass, return true.
        return true;
    }

    /**
     * Check if a message is valid.
     *
     * To do this, we try to unarmor the message. If the operation is successful, then we consider that
     * the message is a valid one.
     *
     * @param string $armored ASCII armored message data
     * @return bool true if valid, false otherwise
     */
    public function isValidMessage($armored)
    {
        // First, we try to get the marker of the message.
        try {
            $marker = $this->getGpgMarker($armored);
        } catch (Exception $e) {
            // If we can't extract the marker, we consider it's not a valid gpg message.
            return false;
        }
        // Do not accept keys as valid marker
        if ($marker != 'PGP MESSAGE') {
            return false;
        }
        // Try to unarmor the key.
        $unarmored = OpenPGP::unarmor($armored, $marker);
        // If we don't manage to unarmor the message, we consider it's not a valid one.
        if ($unarmored != false) {
            return true;
        }

        return false;
    }

    /**
     * Get public key information.
     *
     * @param string $armoredKey the ASCII armored key block
     * @return array as described above
     * @throws Exception if the armored key is not parsable as public key
     */
    public function getPublicKeyInfo($armoredKey)
    {
        if ($this->isParsableArmoredPublicKeyRule($armoredKey) === false
            && $this->isParsableArmoredPrivateKeyRule($armoredKey) === false) {
            throw new Exception('The public key could not be parsed.');
        }

        return $this->getKeyInfo($armoredKey);
    }

    /**
     * Get key information
     *
     * Extract the information from the key and return them in an array
     * Information returned :
     *  - fingerprint   : fingerprint of the key, string(40)
     *  - bits          : size / number of bits (int)
     *  - type          : algorithm used by the key (RSA, ELGAMAL, DSA, etc..)
     *  - key_id        : key id, string(8)
     *  - key_created   : date of creation of the key, timestamp
     *  - uid           : user id of the key following gpg standard (usually name surname (comment) <email>), string
     *  - expires       : expiration date or empty if no expiration date, timestamp
     *
     * Important note : using OpenPgp-PHP library instead of the standard php lib php-gnupg to validate the key
     * as indicated above.
     *
     * @param string $armoredKey the ASCII armored key block
     * @return array as described above
     */
    public function getKeyInfo($armoredKey)
    {
        // Unarmor the key.
        $keyUnarmored = OpenPGP::unarmor(
            $armoredKey,
            $this->getGpgMarker($armoredKey)
        );

        // Get the message.
        $msg = OpenPGP_Message::parse($keyUnarmored);

        // Parse public key.
        $publicKey = OpenPGP_PublicKeyPacket::parse($keyUnarmored);

        // Get Packets for public key.
        $publicKeyPacket = $msg->packets[0];

        // If the packet is not a valid publicKey Packet, then we can't retrieve the uid.
        if (!$publicKeyPacket instanceof OpenPGP_PublicKeyPacket) {
            throw new Exception('Invalid key');
        }

        // Get userId.
        $userIds = [];
        foreach ($msg->signatures() as $signatures) {
            foreach ($signatures as $signature) {
                if ($signature instanceof \OpenPGP_UserIDPacket) {
                    $userIds[] = sprintf('%s', $signature);
                }
            }
        }

        // Retrieve algorithm type.
        $type = OpenPGP_PublicKeyPacket::$algorithms[$publicKeyPacket->algorithm];

        // Retrieve key size.
        $bits = 0;
        if (isset(OpenPGP_PublicKeyPacket::$key_fields[$publicKeyPacket->algorithm])) {
            $keyFirstElt = OpenPGP_PublicKeyPacket::$key_fields[$publicKeyPacket->algorithm][0];
            $bits = OpenPGP::bitlength($publicKeyPacket->key[$keyFirstElt]);
        }

        // Build key information array.
        $info = [
            'fingerprint' => $publicKeyPacket->fingerprint(),
            'bits' => $bits,
            'type' => $type,
            'key_id' => $publicKeyPacket->key_id,
            'key_created' => $publicKey->timestamp,
            'uid' => $userIds[0],
            'expires' => $publicKeyPacket->expires($msg),
        ];

        return $info;
    }

    /**
     * Get key information from keyring
     *
     * @param string $fingerprint key fingerpint
     * @return mixed
     */
    public function getKeyInfoFromKeyring($fingerprint)
    {
        // Return info read from the keyring.
        return $this->_gpg->keyinfo($fingerprint);
    }

    /**
     * Import a key into the local keyring.
     *
     * @param string $armoredKey the ASCII armored key block
     * @return string fingerprint of the key
     * @throws Exception
     */
    public function importKeyIntoKeyring($armoredKey)
    {
        $import = $this->_gpg->import($armoredKey);
        if (!is_array($import)) {
            throw new Exception('Could not import the key.');
        }

        return $import['fingerprint'];
    }

    /**
     * Remove a key from the local keyring.
     *
     * @param string $fingerprint of the key
     * @throws Exception
     * @return void
     */
    public function removeKeyFromKeyring($fingerprint)
    {
        $deleting = $this->_gpg->deletekey($fingerprint, true);
        if (!$deleting) {
            throw new Exception('Could not delete the key.');
        }
    }

    /**
     * Encrypt a text.
     *
     * @param string $text plain text to be encrypted.
     * @param bool $sign whether the encrypted message should be signed. Do not forget to add a sign key before.
     * @return mixed encrypted text
     * @throws Exception
     */
    public function encrypt($text, $sign = false)
    {
        // If no private key is set, throw exception.
        if (empty($this->encryptKeyInfo)) {
            throw new Exception('No public key was added');
        }

        // Add encrypt key.
        $this->_gpg->addencryptkey($this->encryptKeyInfo['fingerprint']);

        // If user wants to sign the message.
        if ($sign === true) {
            // If no sign key has been set before, throw an exception.
            if (empty($this->signKeyInfo)) {
                throw new Exception('No sign key has been added.');
            }
            // Add sign key.
            $this->_gpg->addsignkey($this->signKeyInfo['fingerprint']);
            // Encrypt message.
            $encryptedText = $this->_gpg->encryptsign($text);
        } else {
            // Else, if user didn't request signing.
            // Encrypt message.
            $encryptedText = $this->_gpg->encrypt($text);
        }

        return $encryptedText;
    }

    /**
     * Decrypt a text.
     *
     * @param string $text GPG block text to be decrypted.
     * @param string $passphrase passphrase for the key.
     *  Warning : keys with passphrase is not currently supported. Leave this argument empty for now.
     *  This is a php gnupg limitation.
     * @param bool $verifySignature should signature be verified
     * @param array $signatureInfo signature data (optional)
     * @return mixed decrypted text if success, false if couldn't decrypt.
     * @throws Exception
     */
    public function decrypt($text, $passphrase = '', $verifySignature = false, &$signatureInfo = [])
    {
        $decrypted = false;

        // If no private key is set, throw exception.
        if (empty($this->decryptKeyInfo)) {
            throw new Exception('No private key was added.');
        }

        // If a passphrase is provided, throw an exception.
        if ($passphrase !== '') {
            throw new Exception('Private keys with passphrase is not supported.');
        }

        // Add decrypt key.
        $this->_gpg->adddecryptkey($this->decryptKeyInfo['fingerprint'], $passphrase);

        if ($verifySignature === false) {
            // Decrypt the text.
            $decrypted = $this->_gpg->decrypt($text);
        } else {
            // Decrypt text with signature verification.
            $signatureInfo = $this->_gpg->decryptverify($text, $decrypted);
        }

        // Return decrypted text.
        return $decrypted;
    }

    /**
     * Verify a signed message.
     *
     * @param string $armored The armored signed message to verify.
     * @param string $fingerprint The fingerprint of the key to verify for.
     * @param mixed $plainText (optional) The plain text. If this optional parameter is passed, it is filled with the plain text.
     * @return void
     * @throws \Exception If the armored signed message cannot be verified.
     */
    public function verify($armored, $fingerprint, &$plainText = null)
    {
        $signature = $this->_gpg->verify($armored, false, $plainText);
        if ($signature[0]['fingerprint'] !== $fingerprint) {
            throw new \Exception(__('The message cannot be verified.'));
        }
    }
}
