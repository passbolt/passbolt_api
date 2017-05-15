<?php
namespace Passbolt;

use \Exception as Exception;
use \OpenPGP as OpenPGP;

/**
 * Gpg wrapper utility
 *
 * This class provides tools for Gpg operations.
 * It is based on 2 different GPG libraries : Php Gnupg, and Openpgp-php
 *
 * We use 2 libraries instead of ones for the reasons mentioned below :
 *
 *  - bugs : Php Gnupg is badly faulty. On some basic operations it simple provokes segmentation fault,
 *  which we cannot allow in our code. Segmentation faults happen when we try to import
 *  a key with an invalid format for instance.
 *  Since we need to validate keys, we simply cannot rely on Php Gnupg for this type of operations.
 *  For all operations such as key validation, or information extraction we prefer to use Openpgp-php
 *
 *  - Velocity : Openpgp-php is a great library, but being implemented in PHP it can't be as fast as Php Gnupg.
 * So for all encryptions / decryption operations, we will prefer Php Gnupg
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Gpg {

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
	public function __construct() {
		if (!extension_loaded('gnupg')) {
			throw new Exception('PHP Gnupg library is not installed');
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
	public function setEncryptKey($armoredKey) {
		// Get the key info.
		$this->encryptKeyInfo = $this->getKeyInfo($armoredKey);

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
	public function setDecryptKey($armoredKey) {
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
	public function setSignKey($armoredKey) {
		// Get the key info.
		$this->signKeyInfo = $this->getKeyInfo($armoredKey);

		// Import key inside the keyring.
		$this->importKeyIntoKeyring($armoredKey);

		// Store armored key.
		$this->signKey = $armoredKey;

		return true;
	}

/**
 * Get the key marker.
 *
 * @param string $armoredKey ASCII armored key data
 * @return mixed
 * @throws Exception
 */
	public function getKeyMarker($armoredKey) {
		$isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $armoredKey, $values);
		if (!$isMarker || !isset($values[2])) {
			throw new Exception('No marker found in the key');
		}

		return $values[2];
	}

/**
 * Check if a key is valid.
 *
 * To do this, we try to unarmor the key. If the operation is successful, then we consider that
 * the key is a valid one.
 *
 * @param string $armoredKey ASCII armored key data
 * @return bool true if valid, false otherwise
 */
	public function isValidKey($armoredKey) {
		// First, we try to get the marker of the key.
		try {
			$marker = $this->getKeyMarker($armoredKey);
		} catch (Exception $e) {
			// If we can't extract the marker, we consider it's not a valid key.
			return false;
		}

		// Try to unarmor the key.
		$keyUnarmored = OpenPGP::unarmor($armoredKey, $marker);
		// If we don't manage to unarmor the key, we consider it's not a valid one.
		if ($keyUnarmored == false) {
			return false;
		}

		// All tests pass, return true.
		return true;
	}

/**
 * Get key information.
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
 * Important note :
 * To extract these information, we are using OpenPgp-PHP library instead of the standard php lib php-gnupg.
 * This is because php-gnupg gets some pretty serious segmentation faults for basic operations.
 * For instance, trying to read / import an invalid key with php-gnupg will throw a segmentation fault.
 * So for basic operations such as getting the key info, or checking if the key is valid, we prefer to use a full php implementation
 * to avoid awful crash of the app.
 *
 * @param string $armoredKey the ASCII armored key block
 * @return array as described above
 * @throws Exception
 */
	public function getKeyInfo($armoredKey) {
		if ($this->isValidKey($armoredKey) === false) {
			throw new Exception('Invalid key');
		}

		// Unarmor the key.
		$keyUnarmored = OpenPGP::unarmor(
			$armoredKey,
			$this->getKeyMarker($armoredKey)
		);

		// Get the message.
		$msg = \OpenPGP_Message::parse($keyUnarmored);

		// Parse public key.
		$publicKey = \OpenPGP_PublicKeyPacket::parse($keyUnarmored);

		// Get Packets for public key.
		$publicKeyPacket = $msg->packets[0];

		// If the packet is not a valid publicKey Packet, then we can't retrieve the uid.
		if (!$publicKeyPacket instanceof \OpenPGP_PublicKeyPacket) {
			throw new Exception('Invalid key');
		}

		// Get self signatures.
		$selfSignatures = $publicKeyPacket->self_signatures($msg);

		// Get userId.
		$userIds = [];
		foreach ($msg->signatures() as $signatures) {
			foreach ($signatures as $signature) {
				if ($signature instanceof \OpenPGP_UserIDPacket) {
					$userIds[] = sprintf('%s', $signature);
				}
			}
		}

		$type = '';
		$bits = '';
		if (count($selfSignatures) > 0) {
			$type = $selfSignatures[0]->key_algorithm_name();
			$bits = OpenPGP::bitlength($selfSignatures[0]->data[0]);
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
	public function getKeyInfoFromKeyring($fingerprint) {
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
	public function importKeyIntoKeyring($armoredKey) {
		$import = $this->_gpg->import($armoredKey);
		if (!is_array($import)) {
			throw new Exception('Could not import the key');
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
	public function removeKeyFromKeyring($fingerprint) {
		$deleting = $this->_gpg->deletekey($fingerprint, true);
		if (!$deleting) {
			throw new Exception('Could not delete the key');
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
	public function encrypt($text, $sign = false) {
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
				throw new Exception('No sign key has been added');
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
 * @param array &$signatureInfo signature data (optional)
 * @return mixed decrypted text if success, false if couldn't decrypt.
 * @throws Exception
 */
	public function decrypt($text, $passphrase = '', $verifySignature = false, &$signatureInfo = []) {
		$decrypted = false;

		// If no private key is set, throw exception.
		if (empty($this->decryptKeyInfo)) {
			throw new Exception('No private key was added');
		}

		// If a passphrase is provided, throw an exception.
		if ($passphrase !== '') {
			throw new Exception('Private keys with passphrase is not supported yet');
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
}