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
namespace Passbolt\Metadata\Service\OpenPGP;

use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Exception;
use Passbolt\Metadata\Model\Entity\MetadataKey;

trait OpenPGPCommonMetadataOperationsTrait
{
    /**
     * Get the OpenPGP Backend ready to encryption with shared metadata key
     *
     * @param \App\Utility\OpenPGP\OpenPGPBackend $gpg for example OpenPGPBackendFactory::get()
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey Metadata entity object.
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server keys
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    private function setEncryptKeyWithMetadataKey(OpenPGPBackend $gpg, MetadataKey $metadataKey): OpenPGPBackend
    {
        // Set encryption key as the metadata key
        try {
            $this->assertMetadataKey($metadataKey);
        } catch (Exception $exception) {
            if (Configure::read('debug')) {
                Log::error(json_encode($metadataKey));
            }
            $msg = __('Could not validate metadata key data.');
            throw new InternalErrorException($msg, 500, $exception);
        }
        try {
            $gpg->setEncryptKeyFromFingerprint($metadataKey->fingerprint);
        } catch (Exception $exception) {
            // Try to import the key in keyring again
            try {
                $gpg->importKeyIntoKeyring($metadataKey->armored_key);
                $gpg->setEncryptKeyFromFingerprint($metadataKey->fingerprint);
            } catch (Exception $exception) {
                if (Configure::read('debug')) {
                    Log::error(json_encode($metadataKey));
                }
                $msg = __('Could not import the metadata OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey Metadata key entity.
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    private function assertMetadataKey(MetadataKey $metadataKey): void
    {
        if (!isset($metadataKey->armored_key) || !isset($metadataKey->fingerprint)) {
            $msg = __('The metadata key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $fingerprint = $metadataKey->fingerprint;
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The metadata key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $armoredKey = $metadataKey->armored_key;
        if (!is_string($armoredKey) || !PublicKeyValidationService::parseAndValidatePublicKey($armoredKey)) {
            $msg = __('The metadata armored key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }
}
