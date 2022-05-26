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
 * @since         3.6.0
 */
namespace App\Service\OpenPGP;

use App\Utility\OpenPGP\OpenPGPBackendFactory;

/**
 * Public Key encryption check service
 */
class PublicKeyCanEncryptCheckService
{
    /**
     * @param string $armoredKey public key
     * @param string $fingerprint public key fingerprint
     * @throws \App\Error\Exception\CustomValidationException if the key cannot be parsed or is invalid
     * @return bool
     */
    public static function check(string $armoredKey, string $fingerprint): bool
    {
        $gpg = OpenPGPBackendFactory::get();
        $result = true;

        // Remove key if already in keyring
        // We don't want to use the cached version
        if ($gpg->isKeyInKeyring($fingerprint)) {
            $gpg->deleteKey($fingerprint);
        }

        try {
            $messageToEncrypt = 'PublicKeyCanEncryptCheckService check';
            $fingerprint = $gpg->importKeyIntoKeyring($armoredKey);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->encrypt($messageToEncrypt);
        } catch (\Exception $e) {
            $result = false;
        }

        // Remove key if managed to get inserted in keyring
        // We don't want to use the validated version moving forward
        $gpg->clearKeys();
        if ($gpg->isKeyInKeyring($fingerprint)) {
            $gpg->deleteKey($fingerprint);
        }

        return $result;
    }
}
