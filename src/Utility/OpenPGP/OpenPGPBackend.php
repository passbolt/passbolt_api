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
namespace App\Utility\OpenPGP;

use App\Utility\OpenPGP\Traits\OpenPGPBackendArmoredParseTrait;
use App\Utility\OpenPGP\Traits\OpenPGPBackendClearPropsTrait;
use App\Utility\OpenPGP\Traits\OpenPGPBackendGetKeyInfoTrait;
use App\Utility\OpenPGP\Traits\OpenPGPBackendGetMessageInfoTrait;
use App\Utility\OpenPGP\Traits\OpenPGPCommonAssertsTrait;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Http\Exception\InternalErrorException;

abstract class OpenPGPBackend implements OpenPGPBackendInterface
{
    use OpenPGPBackendArmoredParseTrait;
    use OpenPGPCommonAssertsTrait;
    use OpenPGPBackendClearPropsTrait;
    use OpenPGPBackendGetKeyInfoTrait;
    use OpenPGPBackendGetMessageInfoTrait;

    /**
     * @var string|null fingerprint of the key set to decrypt
     */
    protected $_decryptKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to encrypt
     */
    protected $_encryptKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to encrypt
     */
    protected $_signKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to verify signature
     */
    protected $_verifyKeyFingerprint;

    /**
     * Constructor.
     *
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __construct()
    {
        $this->_encryptKeyFingerprint = null;
        $this->_decryptKeyFingerprint = null;
        $this->_signKeyFingerprint = null;
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return void
     */
    public function setVerifyKeyFromFingerprint(string $fingerprint): void
    {
        $this->_verifyKeyFingerprint = $fingerprint;
    }

    /**
     * Import server key in keyring
     *
     * @throws \Cake\Http\Exception\InternalErrorException if server key is undefined or invalid
     * @return void
     */
    public function importServerKeyInKeyring(): void
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $keyFilePath = Configure::read('passbolt.gpg.serverKey.private');

        // If it's not in keyring try to import it
        // Check if file containing the private key exist
        if ($keyFilePath === null) {
            throw new InternalErrorException('The secret key file is not defined.');
        }
        if (!file_exists($keyFilePath)) {
            $msg = __('The OpenPGP server key defined in the config is not found in the file system.');
            throw new InternalErrorException($msg);
        }
        $privateKey = file_get_contents($keyFilePath);
        if ($privateKey === false) {
            $msg = __('The OpenPGP server key defined in the config cannot be opened.');
            throw new InternalErrorException($msg);
        }
        if (!$this->isParsableArmoredPrivateKey($privateKey)) {
            $msg = __('The OpenPGP server key defined on file is not a valid private key.');
            throw new InternalErrorException($msg);
        }

        // try to import it
        $this->importKeyIntoKeyring($privateKey);
        if (!$this->isKeyInKeyring($fingerprint)) {
            $msg = __('There is an issue with the OpenPGP server key.') . ' ';
            $msg .= __('The fingerprint does not match the one associated with the key on file.');
            throw new InternalErrorException($msg);
        }
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
    public function isValidMessage(string $armored): bool
    {
        try {
            $this->assertGpgMarker($armored, self::MESSAGE_MARKER);
        } catch (CakeException $e) {
            return false;
        }

        $unarmored = $this->unarmor($armored, self::MESSAGE_MARKER);

        return $unarmored !== false;
    }

    /**
     * Return true if string is a valid fingerprint
     *
     * @param mixed $fingerprint user provided data
     * @return bool
     */
    public static function isValidFingerprint($fingerprint = null): bool
    {
        if (!isset($fingerprint) || !is_string($fingerprint)) {
            return false;
        }

        return preg_match('/^[A-F0-9]{40}$/', $fingerprint) === 1;
    }

    // ---------------------------
    // MISC UTILITIES
    // ---------------------------

    /**
     * @param string $fingerprint 40 char
     * @return string long key id 16 char
     * @throws \Exception
     */
    public static function fingerprintToKeyId(string $fingerprint)
    {
        if (strlen($fingerprint) !== 40) {
            throw new \Exception('Invalid fingerprint.');
        }

        return substr($fingerprint, -16);
    }

    /**
     * Key with extra breakline after checksum and before the end block
     * are known to cause compatibility issues with gopenpgp
     *
     * @param string $armoredKey armored key block
     * @return bool
     */
    public static function hasExtraBreakLine(string $armoredKey): bool
    {
        $armoredKey = trim($armoredKey);
        $array = explode("\n", $armoredKey);
        $size = count($array);
        if ($size < 2) {
            return true;
        }
        // If the line before the last line is empty
        if (empty($array[$size - 2])) {
            return true;
        }

        return false;
    }
}
