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

use App\Utility\OpenPGP\OpenPGPBackend;

/**
 * Public Key revocation check service
 */
class PublicKeyRevocationCheckService
{
    /**
     * @param string $armoredKey public key
     * @throws \App\Error\Exception\CustomValidationException if the key cannot be parsed or is invalid
     * @return bool
     */
    public function check(string $armoredKey): bool
    {
        $rules = PublicKeyValidationService::getHistoricalRules();
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, $rules);

        // Must contain a revocation packet
        if (!$keyInfo['revoked']) {
            return false;
        }

        // Check revocation packet signature
        if ($keyInfo['type'] === 'RSA') {
            $publicKey = \OpenPGP_Message::parse(\OpenPGP::unarmor($armoredKey));
            $toVerify = new \OpenPGP_Crypt_RSA($publicKey);
            $signatures = $toVerify->verify($publicKey);

            return $this->searchForRevocation($signatures, $keyInfo['key_id']);
        } else {
            // TODO use php-gnupg revoked flag
            return $keyInfo['revoked'];
        }
    }

    /**
     * @param array $signatures array of OpenPGP_SignaturePacket or array of array of OpenPGP_SignaturePacket
     * @param string $longKeyId long key id
     * @return bool
     */
    private function searchForRevocation(array $signatures, string $longKeyId): bool
    {
        foreach ($signatures as $signature) {
            if (is_array($signature)) {
                if ($this->searchForRevocation($signature, $longKeyId)) {
                    return true;
                }
            }
            if ($signature instanceof \OpenPGP_SignaturePacket) {
                if (OpenPGPBackend::containsRevocationSignature($signature, $longKeyId)) {
                    return true;
                }
            }
        }

        return false;
    }
}
