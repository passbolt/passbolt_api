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
 * @since         2.0.0
 */
namespace App\Utility\OpenPGP\Backends;

//use \Exception as Exception;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
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
class Gnupg implements OpenPGPBackend
{
    /**
     * @var string fingerprint of the key set to decrypt
     */
    private $_decryptKey;

    /**
     * @var string fingerprint of the key set to encrypt
     */
    private $_encryptKey;

    /**
     * @var string fingerprint of the key set to encrypt
     */
    private $_signKey;

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

        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
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
    public function setEncryptKey(string $armoredKey)
    {
        // Get the key info.
        $encryptKeyInfo = $this->getPublicKeyInfo($armoredKey);
        $fingerprint = $encryptKeyInfo['fingerprint'];

        // Import key inside the keyring if it's not already there
        if (!$this->isKeyInKeyring($fingerprint)) {
            $this->importKeyIntoKeyring($armoredKey);
        }
        // Store armored key.
        $this->_encryptKey = $fingerprint;
        $this->_gpg->addencryptkey($fingerprint);

        return true;
    }

    /**
     * Set a key for encryption.
     *
     * @param string $fingerprint fingerprint
     * @return bool
     * @throws Exception
     */
    public function setEncryptKeyFromFingerprint(string $fingerprint)
    {
        if (!$this->isKeyInKeyring($fingerprint)) {
            throw new Exception(__('Key {0} not found in the keyring', $fingerprint));
        }
        $this->_encryptKey = $fingerprint;
        $this->_gpg->addencryptkey($fingerprint);

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase to decrypt secret key
     * @throws exception if passphrase is not empty
     * @throws exception this key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKey(string $armoredKey, string $passphrase)
    {
        // Get the key info.
        $decryptKeyInfo = $this->getKeyInfo($armoredKey);
        $fingerprint = $decryptKeyInfo['fingerprint'];

        // Import key inside the keyring if it's not already there
        if (!$this->isKeyInKeyring($fingerprint)) {
            $this->importKeyIntoKeyring($armoredKey);
        }

        // If a passphrase is provided, throw an exception.
        if ($passphrase !== '') {
            throw new Exception('Secret keys with a passphrase are not supported.');
        }

        // Add decrypt key.
        $this->_decryptKey = $fingerprint;
        if (!$this->_gpg->adddecryptkey($fingerprint, $passphrase)) {
            throw new Exception('The secret key {0} cannot be used to decrypt.', $fingerprint);
        }

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $fingerprint fingerprint of a key in the keyring
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key is using a passphrase
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKeyFromFingerprint(string $fingerprint, string $passphrase = '')
    {
        // Get the key info.
        // Import key inside the keyring.
        if (!$this->isKeyInKeyring($fingerprint)) {
            throw new Exception(__('Key {0} not found in the keyring', $fingerprint));
        }

        // If a passphrase is provided, throw an exception.
        if ($passphrase !== '') {
            throw new Exception('Secret keys with a passphrase are not supported.');
        }

        // Add decrypt key.
        $this->_decryptKey = $fingerprint;
        if (!$this->_gpg->adddecryptkey($fingerprint, $passphrase)) {
            throw new Exception('The secret key {0} cannot be used to decrypt.', $fingerprint);
        }

        return true;
    }

    /**
     * Set a key for signing.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase passphrase
     * @return bool
     * @throws Exception
     */
    public function setSignKey(string $armoredKey, string $passphrase)
    {
        // Get the key info.
        $signKeyInfo = $this->getKeyInfo($armoredKey);
        $fingerprint = $signKeyInfo['fingerprint'];

        // Import key inside the keyring if needed
        if (!$this->isKeyInKeyring($fingerprint)) {
            $this->importKeyIntoKeyring($armoredKey);
        }
        // If a passphrase is provided, throw an exception.
        if ($passphrase !== '') {
            throw new Exception(__('Secret keys with a passphrase are not supported.'));
        }

        // Store armored key.
        $this->_signKey = $fingerprint;
        try {
            $this->_gpg->addsignkey($fingerprint, $passphrase);
        } catch (\Exception $e) {
            throw new Exception(__('Could not use key {0} for signing.', $fingerprint));
        }
        return true;
    }

    /**
     * Set key to be used for signing
     *
     * @throws Exception if the key is not already in the keyring
     * @param string $fingerprint
     * @param string $passphrase
     */
    public function setSignKeyFromFingerprint(string $fingerprint, string $passphrase)
    {
        if (!$this->isKeyInKeyring($fingerprint)) {
            throw new Exception(__('Key {0} not found in the keyring', $fingerprint));
        }

        // If a passphrase is provided, throw an exception.
        if ($passphrase !== '') {
            throw new Exception('Secret keys with a passphrase are not supported.');
        }

        $this->_signKey = $fingerprint;
        $this->_gpg->addsignkey($fingerprint, $passphrase);
    }

    /**
     * Get the gpg marker.
     *
     * @param string $armored ASCII armored gpg data
     * @return mixed
     * @throws Exception
     */
    private function getGpgMarker(string $armored)
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
    public function isParsableArmoredPublicKey(string $armoredKey)
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
    public function isParsableArmoredPrivateKey(string $armoredKey)
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
     * Check if a message is valid.
     *
     * To do this, we try to unarmor the message. If the operation is successful, then we consider that
     * the message is a valid one.
     *
     * @param string $armored ASCII armored message data
     * @return bool true if valid, false otherwise
     */
    public function isValidMessage(string $armored)
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
        // Try to unarmor the message.
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
    public function getPublicKeyInfo(string $armoredKey)
    {
        if ($this->isParsableArmoredPublicKey($armoredKey) === false
            && $this->isParsableArmoredPrivateKey($armoredKey) === false) {
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
    public function getKeyInfo(string $armoredKey)
    {
        // Unarmor the key.
        $keyUnarmored = OpenPGP::unarmor($armoredKey, $this->getGpgMarker($armoredKey));

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
     * @return array|false
     */
    public function getKeyInfoFromKeyring(string $fingerprint)
    {
        try {
            $results = $this->_gpg->keyinfo($fingerprint);
        } catch(\Exception $e) {
            return false;
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    /**
     * Is key currently in keyring
     *
     * @param string $fingerprint
     * @return bool
     */
    public function isKeyInKeyring(string $fingerprint)
    {
        try {
            $results = $this->_gpg->keyinfo($fingerprint);
        } catch(\Exception $e) {
            return false;
        }
        if ($results === false) {
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
     * @return array information about the key
     * @throws Exception
     */
    public function importKeyIntoKeyring(string $armoredKey)
    {
        $import = $this->_gpg->import($armoredKey);
        if (!is_array($import)) {
            throw new Exception('Could not import the key.');
        }

        return $import;
    }

    /**
     * Encrypt a text.
     *
     * @param string $text plain text to be encrypted.
     * @param bool $sign whether the encrypted message should be signed. Do not forget to add a sign key before.
     * @return mixed encrypted text
     * @throws Exception
     */
    public function encrypt(string $text, bool $sign = false)
    {
        // If no public key is set, throw exception.
        if (empty($this->_encryptKey)) {
            throw new Exception('No public key was added');
        }

        // If user wants to sign the message.
        if ($sign === true) {
            // If no sign key has been set before, throw an exception.
            if (empty($this->_signKey)) {
                throw new Exception('No sign key has been added.');
            }
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
     *  Warning : keys with passphrase is not currently supported. Leave this argument empty for now.
     *  This is a php gnupg limitation.
     * @param bool $verifySignature should signature be verified
     * @param array $signatureInfo signature data (optional)
     * @return mixed decrypted text if success, false if couldn't decrypt.
     * @throws Exception
     */
    public function decrypt(string $text, bool $verifySignature = false, array &$signatureInfo = [])
    {
        $decrypted = false;

        // If no private key is set, throw exception.
        if (empty($this->_decryptKey)) {
            throw new Exception('No private key was added.');
        }

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
     * Setup server key
     * Import server key in the keyring if necessary
     *
     * @param string|null $fingerprint fingerprint of server key
     * @param string|null $keyFilePath full path to the secret key file
     * @throw Exception if config is invalid
     * @throw Exception server key cannot be read on file
     * @throw Exception server key on file is not a valid private key
     * @throw Exception server key fingerprint on file is not the same than in config
     * @return bool true if success
     */
    public function setUpServerKey(string $fingerprint = null, string $keyFilePath = null) {
        // Check if config contains fingerprint
        if ($fingerprint === null) {
            throw new Exception(__('The GnuPG config for the server is not available or incomplete.'));
        }

        // Check if key associated with fingerprint is in keyring
        if ($this->isKeyInKeyring($fingerprint)) {
            return true;
        }

        // If it's not in keyring try to import it
        // Check if file containing the private key exist
        if ($keyFilePath === null) {
            throw new Exception(__('The secret key file is not defined.'));
        }
        $msg = __('The OpenPGP server key defined in the config could not be found in the GnuPG keyring and file system.');
        if (!file_exists($keyFilePath)) {
            throw new Exception($msg);
        }
        $privateKey = file_get_contents($keyFilePath);
        if ($privateKey === false) {
            throw new Exception($msg);
        }
        if (!$this->isParsableArmoredPrivateKey($privateKey)) {
            $msg = __('The OpenPGP server key defined on file is not a valid private key.');
            throw new Exception($msg);
        }

        // try to import it
        $this->importKeyIntoKeyring($privateKey);
        if (!$this->isKeyInKeyring($fingerprint)) {
            $msg = __('The OpenPGP server key fingerprint defined in the config does not match the one associated with the key on file.');
            throw new Exception($msg);
        }
        return true;
    }
}
