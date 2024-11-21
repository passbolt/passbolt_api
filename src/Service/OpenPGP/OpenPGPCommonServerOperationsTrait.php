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
 * @since         4.10.0
 */
namespace App\Service\OpenPGP;

use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

trait OpenPGPCommonServerOperationsTrait
{
    /**
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server key to encrypt
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    public function setEncryptKeyWithServerKey(OpenPGPBackend $gpg): OpenPGPBackend
    {
        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertServerFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // set the key to be used for encrypting
        try {
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to encrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server key to sign
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    public function setDecryptKeyWithServerKey(OpenPGPBackend $gpg): OpenPGPBackend
    {
        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertServerFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // set the key to be used for decrypting
        try {
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server key to verify
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    public function setVerifyKeyWithServerKey(OpenPGPBackend $gpg): OpenPGPBackend
    {
        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertServerFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // Set verify key as the one from the server
        try {
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setVerifyKeyFromFingerprint($fingerprint);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to verify signature.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server key to sign
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    public function setSignKeyWithServerKey(OpenPGPBackend $gpg): OpenPGPBackend
    {
        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertServerFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // Set sign key as the one from the server
        try {
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to sign.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param mixed $fingerprint fingerprint
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the server key fingerprint cannot be loaded
     */
    private function assertServerFingerprint($fingerprint): void
    {
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The config for the server private key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param mixed $passphrase passphrase
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the server key passphrase cannot be loaded
     */
    private function assertServerPassphrase($passphrase): void
    {
        if (!is_string($passphrase)) {
            $msg = __('The config for the server private key passphrase is invalid.');
            throw new InternalErrorException($msg);
        }
    }
}
