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

use App\Model\Entity\Gpgkey;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;

trait OpenPGPCommonUserOperationsTrait
{
    /**
     * Get the OpenPGP Backend ready to encryption with user key
     *
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @param \App\Model\Entity\Gpgkey $userKey entity
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use user key to encrypt
     * @throws \Cake\Http\Exception\InternalErrorException if the user key cannot be loaded
     */
    protected function setEncryptKeyWithUserKey(OpenPGPBackend $gpg, Gpgkey $userKey): OpenPGPBackend
    {
        // Set encryption key as the one from the user
        try {
            $this->assertUserKey($userKey);
        } catch (\Exception $exception) {
            $msg = __('Could not validate user data.');
            throw new InternalErrorException($msg, 500, $exception);
        }
        try {
            $gpg->setEncryptKeyFromFingerprint($userKey->fingerprint);
        } catch (\Exception $exception) {
            // Try to import the key in keyring again
            try {
                $gpg->importKeyIntoKeyring($userKey->armored_key);
                $gpg->setEncryptKeyFromFingerprint($userKey->fingerprint);
            } catch (\Exception $exception) {
                if (Configure::read('debug')) {
                    Log::error(json_encode($userKey));
                }
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * Get the OpenPGP Backend ready to verify with user key
     *
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @param \App\Model\Entity\Gpgkey $userKey entity
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use user key to verify
     * @throws \Cake\Http\Exception\InternalErrorException if the user key cannot be loaded
     */
    protected function setVerifyKeyWithUserKey(OpenPGPBackend $gpg, Gpgkey $userKey): OpenPGPBackend
    {
        // Set encryption key as the one from the user
        try {
            $this->assertUserKey($userKey);
        } catch (\Exception $exception) {
            $msg = __('Could not validate user data.');
            throw new InternalErrorException($msg, 500, $exception);
        }
        try {
            $gpg->setVerifyKeyFromFingerprint($userKey->fingerprint);
        } catch (\Exception $exception) {
            // Try to import the key in keyring again
            try {
                $gpg->importKeyIntoKeyring($userKey->armored_key);
                $gpg->setVerifyKeyFromFingerprint($userKey->fingerprint);
            } catch (\Exception $exception) {
                if (Configure::read('debug')) {
                    Log::error(json_encode($userKey));
                }
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param \App\Model\Entity\Gpgkey $userKey object as sent from event
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the user key cannot be loaded
     */
    private function assertUserKey(Gpgkey $userKey): void
    {
        if (!isset($userKey->armored_key) || !isset($userKey->fingerprint)) {
            $msg = __('The user public key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $fingerprint = $userKey->fingerprint;
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The user public key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $armoredKey = $userKey->armored_key;
        if (!is_string($armoredKey) || !PublicKeyValidationService::parseAndValidatePublicKey($armoredKey)) {
            $msg = __('The user armored key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }
}
