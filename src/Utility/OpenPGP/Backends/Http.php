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

use App\Utility\OpenPGP\OpenPGPBackend;
use App\Utility\OpenPGP\OpenPGPCommonAsserts;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Http\Client;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;

class Http extends OpenPGPBackend
{
    use OpenPGPCommonAsserts;

    private $_passphrase = null;

    /** @var Client */
    protected $_httpClient = null;

    private $_region;
    private $_project;
    private $_domain;
    private $_url;
    private $_auth;

    private $_decrypt_url;
    private $_encrypt_url;
    private $_keyinfo_url;
    private $_msginfo_url;
    private $_verify_url;

    const KEYRING_PUBLIC = 'keyring-public-';
    const KEYRING_PRIVATE = 'keyring-private-';

    const DECRYPT_ENDPOINT = 'decrypt';
    const ENCRYPT_ENDPOINT = 'encrypt';
    const KEYINFO_ENDPOINT = 'keyinfo';
    const MSGINFO_ENDPOINT = 'msginfo';
    const VERIFY_ENDPOINT = 'verify';

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->_httpClient = new Client;
        $this->_region = Configure::consume('passbolt.gpg.http.region');
        $this->_project = Configure::consume('passbolt.gpg.http.project');
        $this->_domain = Configure::consume('passbolt.gpg.http.domain');
        if (empty($this->_region) || empty($this->_project) || empty($this->_domain)) {
            throw new InternalErrorException('Configuration is missing for OpenPGP backend');
        }
        $this->_url = 'https://' . $this->_region . '-' . $this->_project . '.' . $this->_domain . '/';

        $this->_decrypt_url = $this->_url . Configure::read('passbolt.gpg.http.functions.decrypt');
        $this->_encrypt_url = $this->_url . Configure::read('passbolt.gpg.http.functions.encrypt');
        $this->_keyinfo_url = $this->_url . Configure::read('passbolt.gpg.http.functions.keyinfo');
        $this->_msginfo_url = $this->_url . Configure::read('passbolt.gpg.http.functions.msginfo');
        $this->_verify_url = $this->_url . Configure::read('passbolt.gpg.http.functions.verify');

        $this->_auth = [
            'username' => Configure::consume('passbolt.gpg.http.auth.username'),
            'password' => Configure::consume('passbolt.gpg.http.auth.password')
        ];

        if (empty($this->_auth) || empty($this->_auth['username']) || empty($this->_auth['password'])) {
            throw new InternalErrorException('Configuration is missing for OpenPGP backend');
        }
    }

    /**
     * Return a url for a target endpoint
     *
     * @param string $endpoint such as encrypt, decrypt, etc.
     * @throws InternalErrorException if operation is not supported
     * @return string url of the service to call
     */
    private function _getUrl(string $endpoint) {
        switch ($endpoint) {
            case self::ENCRYPT_ENDPOINT:
                return $this->_encrypt_url;
            case self::DECRYPT_ENDPOINT:
                return $this->_decrypt_url;
            case self::KEYINFO_ENDPOINT:
                return $this->_keyinfo_url;
            case self::MSGINFO_ENDPOINT:
                return $this->_msginfo_url;
            case self::VERIFY_ENDPOINT:
                return $this->_verify_url;
            default:
                throw new InternalErrorException('OpenPGP operation not supported.');
        }
    }

    /**
     * Assert that a key is not expired
     *
     * @param array $info see getKeyInfo
     */
    private function _assertKeyNotExpired(array $info) {
        if (!empty($info) && !empty($info['expires'])) {
            if($info['expires'] < time()) {
                throw new Exception('The key expired.');
            }
        }
    }

    /**
     * Assert that a key is not expired
     *
     * @param array $info see getKeyInfo
     */
    private function _assertKeyIsPublic(array $info) {
        if (!$info['public']) {
            throw new Exception('The key is not a public key.');
        }
    }

    /**
     * Assert that a key is not expired
     *
     * @param array $info see getKeyInfo
     */
    private function _assertKeyIsPrivate(array $info) {
        if (!$info['private']) {
            throw new Exception('The key is not a private key.');
        }
    }

    /**
     * Call an endpoint with given data
     *
     * @param string $endpoint such as decrypt, encrypt, etc.
     * @param array $data mixed example ['armored' => 'etc.']
     * @throws BadRequestException if the operation didn't succeed because of user data
     * @throws InternalErrorException if the operation didn't succeed because of config or network issues
     * @return mixed string|array|null json data
     */
    private function _post(string $endpoint, array $data)
    {
        $url = $this->_getUrl($endpoint);
        $response = $this->_httpClient->post($url, json_encode($data), [
            'type' => 'json',
            'auth' => $this->_auth
        ]);
        switch($response->getStatusCode()) {
            case 200:
                $json = $response->getJson();
                return $json;
            case 400:
                Log::error('OpenPGP HTTP: ' . $response->getStringBody());
                $json = $response->getJson();
                throw new BadRequestException($json['code']);
                break;
            default:
                throw new InternalErrorException($response->getStringBody());
                break;
        }
    }

    /**
     * Set a key for encryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @throws Exception if the key cannot be used to encrypt
     * @return bool true if success
     */
    public function setEncryptKey(string $armoredKey)
    {
        $info = $this->getPublicKeyInfo($armoredKey);
        $fingerprint = $info['fingerprint'];
        try {
            $this->_assertKeyNotExpired($info);
            $this->_assertKeyIsPublic($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} can not be used to encrypt.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }

        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
        } catch(Exception $exception) {
            $this->_importKeyIntoKeyring($info, self::KEYRING_PUBLIC);
        }

        $this->_encryptKeyFingerprint = $fingerprint;

        return true;
    }

    /**
     * Set a key for encryption.
     *
     * @param string $fingerprint fingerprint
     * @throws Exception if key is not present in keyring
     * @throws Exception if there was an issue to use the key to encrypt
     * @return bool true if success
     */
    public function setEncryptKeyFromFingerprint(string $fingerprint)
    {
        try {
            $info = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
            $this->_assertKeyNotExpired($info);
            $this->_assertKeyIsPublic($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} cannot be used to encrypt.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }
        $this->_encryptKeyFingerprint = $fingerprint;

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $armoredKey ASCII armored key data
     * @param string $passphrase to decrypt secret key
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKey(string $armoredKey, string $passphrase)
    {
        $info = $this->getKeyInfo($armoredKey);
        $fingerprint = $info['fingerprint'];

        try {
            $this->_assertKeyIsPrivate($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} cannot be used to decrypt.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }
        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
        } catch (Exception $exception) {
            $this->importKeyIntoKeyring($armoredKey);
        }

        $this->_passphrase = $passphrase;
        $this->_encryptKeyFingerprint = $fingerprint;

        return true;
    }

    /**
     * Set a key for decryption.
     *
     * @param string $fingerprint fingerprint of a key in the keyring
     * @param string $passphrase to decrypt secret key
     * @throws Exception if the key cannot be found in the keyring
     * @throws Exception if the key cannot be used to decrypt
     * @return bool true if success
     */
    public function setDecryptKeyFromFingerprint(string $fingerprint, string $passphrase)
    {
        try {
            $info = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
            $this->_assertKeyIsPrivate($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} cannot be used to decrypt.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }

        $this->_passphrase = $passphrase;
        $this->_decryptKeyFingerprint = $fingerprint;

        return true;
    }

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
    public function setSignKey(string $armoredKey, string $passphrase)
    {
        $info = $this->getKeyInfo($armoredKey);
        $fingerprint = $info['fingerprint'];

        try {
            $this->_assertKeyIsPrivate($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} cannot be used to sign.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }

        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
        } catch (Exception $exception) {
            $this->_importKeyIntoKeyring($info, self::KEYRING_PRIVATE);
        }

        $this->_passphrase = $passphrase;
        $this->_signKeyFingerprint = $fingerprint;

        return true;
    }

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
    public function setSignKeyFromFingerprint(string $fingerprint, string $passphrase)
    {
        try {
            $info = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
            $this->_assertKeyNotExpired($info);
            $this->_assertKeyIsPrivate($info);
        } catch (Exception $exception) {
            $msg = __('The key {0} cannot be used to sign.', $fingerprint);
            $msg .= ' ' . $exception->getMessage();
            throw new Exception($msg);
        }

        $this->_passphrase = $passphrase;
        $this->_signKeyFingerprint = $fingerprint;

        return true;
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
        try {
            $this->assertGpgMarker($armoredKey, self::PUBLIC_KEY_MARKER);
            $info = $this->getKeyInfo($armoredKey);
        } catch (Exception $e) {
            return false;
        }

        return $info['public'];
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
    public function isParsableArmoredPrivateKey(string $armoredKey)
    {
        try {
            $this->assertGpgMarker($armoredKey, self::PRIVATE_KEY_MARKER);
            $info = $this->getKeyInfo($armoredKey);
        } catch (Exception $e) {
            return false;
        }

        return $info['private'];
    }

    /**
     * Check if an ASCII armored signed message is parsable
     *
     * @param  string $armored ASCII armored signed message
     * @return bool
     */
    public function isParsableArmoredSignedMessage($armored)
    {
        try {
            $this->assertGpgMarker($armored, self::SIGNED_MESSAGE_MARKER);
            // TODO remote check
        } catch (Exception $e) {
            return false;
        }
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
        try {
            $this->assertGpgMarker($armored, self::MESSAGE_MARKER);
            $this->_post(self::MSGINFO_ENDPOINT, ['armoredText' => $armored]);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get public key information.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws Exception if the armored key cannot be parsed
     * @return array key information (see getKeyInfo)
     */
    public function getPublicKeyInfo(string $armoredKey)
    {
        return $this->getKeyInfo($armoredKey);
    }

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
     * Specific to this backend:
     *  - private  : bool is private key?
     *  - public   : bool is public key?
     *  - armored  : string $armoredKey content
     *
     * @param string $armoredKey the ASCII armored key block
     * @return array as described above
     */
    public function getKeyInfo(string $armoredKey)
    {
        try {
            $info = $this->_post(self::KEYINFO_ENDPOINT, ['armoredKey' => $armoredKey]);
        } catch (BadRequestException $exception) {
            throw new Exception($exception->getMessage());
        }

        $result = [
            'key_id' => strtoupper($info['keyId']),
            'fingerprint' => strtoupper($info['fingerprint']),
            'uid' => $info['userId'],
            'key_created' => strtotime($info['creationTime'])
        ];

        if (!empty($info['expirationTime'])) {
            $result['expires'] = strtotime($info['expirationTime']);
        } else {
            $result['expires'] = null;
        }

        // Approximation for now
        // Until we make this function callers use better format
        if (isset($info['algorithm']['bits'])) {
            $result['type'] = 'RSA';
            $result['bits'] = $info['algorithm']['bits'];
        } else {
            $result['type'] = 'ECC';
            $result['bits'] = 256; //$info['algorithm']['curve'];
        }

        // implementation specific
        $result['armored'] = $armoredKey;
        $result['public'] = $info['isPublic'];
        $result['private'] = $info['isPrivate'];

        return $result;
    }

    /**
     * Is key currently in keyring
     *
     * @param string $fingerprint fingerprint
     * @return bool true if in keyring false otherwise
     */
    public function isKeyInKeyring(string $fingerprint)
    {
        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
            return true;
        } catch (Exception $e) {
        }
        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
            return true;
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Import a key into the local keyring.
     *
     * @param string $armoredKey the ASCII armored key block
     * @throws Exception if the key could not be imported
     * @return string key fingerprint
     */
    public function importKeyIntoKeyring(string $armoredKey)
    {
        try {
            $info = $this->getKeyInfo($armoredKey);
        } catch(Exception $exception) {
            throw new Exception(__('Could not import the key.'));
        }
        $info['armored'] = $armoredKey;
        $fingerprint = $info['fingerprint'];
        $cacheKey = $info['public'] ? self::KEYRING_PUBLIC : self::KEYRING_PRIVATE;
        $cacheKey .= $fingerprint;
        Cache::write($cacheKey, $info);

        return $fingerprint;
    }

    /**
     * Import a key into the local keyring.
     *
     * @param array $info see getKeyInfo
     * @param string $keyring public or private key cache
     * @throws Exception if the key could not be written in cache
     * @return bool
     */
    public function _importKeyIntoKeyring(array $info, string $keyring)
    {
        if ($keyring !== self::KEYRING_PUBLIC && $keyring !== self::KEYRING_PRIVATE) {
            throw new Exception(__('The key could not be added to the keyring. Wrong cache type.'));
        }
        $fingerprint = strtoupper($info['fingerprint']);
        $cacheKey = $keyring . $fingerprint;
        Cache::write($cacheKey, $info);

        return true;
    }

    /**
     * Return key from keyring or null
     *
     * @param string $fingerprint of the key
     * @param string $keyring KEYRING_PUBLIC | KEYRING_PRIVATE
     * @return mixed null
     */
    public function _getKeyFromKeyring(string $fingerprint, string $keyring) {
        $fingerprint = strtoupper($fingerprint);
        $cacheKey = $keyring . $fingerprint;
        $info = Cache::read($cacheKey);
        if (empty($info) || !is_array($info)) {
            $msg = __('The key could not be found in the keyring.');
            throw new Exception($msg);
        }
        return $info;
    }

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
    public function encrypt(string $text, bool $sign = false)
    {
        $this->assertEncryptKey();
        if ($sign) {
            $this->assertSignKey();
        }
        try {
            $fingerprint = $this->_encryptKeyFingerprint;
            $pubKey = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
            $data = [
                'clearText' => $text,
                'publicKey' => [
                    'armored' => $pubKey['armored']
                ]
            ];
            if ($sign) {
                $fingerprint = $this->_signKeyFingerprint;
                $privateKey = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PRIVATE);
                $data['privateKey']['armored'] = $privateKey['armored'];
                $data['privateKey']['passphrase'] = $this->_passphrase;
            }
            $this->_passphrase = null;
            $encryptedText = $this->_post(self::ENCRYPT_ENDPOINT, $data);

        } catch (Exception $exception) {
            // Catch specialized BadRequestException and cast them
            throw new Exception($exception->getMessage());
        }

        return $encryptedText;
    }

    /**
     * Decrypt a text
     *
     * @param string $text ASCII armored encrypted text to be decrypted.
     * @param bool $verifySignature should signature be verified
     * @throws Exception
     * @return string decrypted text
     */
    public function decrypt(string $text, bool $verifySignature = false)
    {
        $this->assertDecryptKey();
        if ($verifySignature) {
            $this->assertVerifyKey();
        }
        try {
            $info = $this->_getKeyFromKeyring($this->_decryptKeyFingerprint, self::KEYRING_PRIVATE);
            $data = [
                'armoredText' => $text,
                'privateKey' => [
                    'armored' => $info['armored'],
                    'passphrase' => $this->_passphrase
                ]
            ];
            if ($verifySignature) {
                $pubKey = $this->_getKeyFromKeyring($this->_verifyKeyFingerprint, self::KEYRING_PUBLIC);
                $data['publicKey']['armored'] = $pubKey['armored'];
            }
            $this->_passphrase = null;
            $response = $this->_post(self::DECRYPT_ENDPOINT, $data);
        } catch (Exception $exception) {
            // Catch specialized BadRequestException and cast them
            Log::error('OpenPGP HTTP: Decryption failed. ' . $exception->getMessage());
            throw new Exception(__('Decryption failed.'));
        }

        if ($verifySignature) {
            $msg = __('Decryption failed.');
            if (empty($response['signatureInfo']) || !isset($response['signatureInfo']['keyId'])) {
                $msg .= ' ' . __('The signature is invalid.');
                throw new Exception($msg);
            }
            $signature = $response['signatureInfo'];
            if (!isset($signature['verified']) || !$signature['verified']) {
                $msg .= ' ' . __('The signature could not be verified.');
                throw new Exception($msg);
            }
            $expect = substr($this->_verifyKeyFingerprint, -16);
            $got = strtoupper($signature['keyId']);
            if ($expect != $got) {
                $msg = ' ' . __('Expecting {0} and got {1} instead.', $expect, $got);
                throw new Exception($msg);
            }
        }
        return $response['clearText'];
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return void
     */
    public function setVerifyKeyFromFingerprint(string $fingerprint)
    {
        try {
            $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
        } catch (Exception $exception) {
            $msg = __('Could not set key to use for signature verification. The public key is not in the keyring.');
            throw new Exception($msg);
        }
        $this->_verifyKeyFingerprint = $fingerprint;
    }

    /**
     * Verify a cleartext signed message.
     *
     * @param string $armored The armored signed message to verify.
     * @param string $fingerprint The fingerprint of the key to verify for.
     * @param mixed $plainText (optional) if this parameter is passed, it will be filled with the plain text.
     * @throws Exception If the armored signed message cannot be verified.
     * @return void
     */
    public function verify($armored, $fingerprint, &$plainText = null)
    {
        try {
            $pubKey = $this->_getKeyFromKeyring($fingerprint, self::KEYRING_PUBLIC);
        } catch(Exception $exception) {
            $msg = __('Verification failed. Fingerprint {0} is not in the keyring.', $fingerprint);
            throw new Exception($msg);
        }
        try {
            $data = [
                'clearText' => $armored,
                'publicKey' => [
                    'armored' => $pubKey['armored']
                ]
            ];
            $response = $this->_post(self::VERIFY_ENDPOINT, $data);
        } catch(Exception $exception) {
            $msg = __('Verification failed.');
            throw new Exception($msg);
        }
        $plainText = $response['clearText'];
    }

    /**
     * Sign a text.
     *
     * @param string $text plain text to be signed.
     * @throws Exception if no key was set to sign
     * @throws Exception if there is an issue with the key to sign
     * @return string signed text
     */
    public function sign(string $text)
    {
        throw new Exception('Sign not implemented');
        return $signedText;
    }
}
