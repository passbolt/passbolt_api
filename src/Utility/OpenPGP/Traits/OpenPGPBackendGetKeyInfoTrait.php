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

namespace App\Utility\OpenPGP\Traits;

use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Log\Log;

trait OpenPGPBackendGetKeyInfoTrait
{
    /**
     * Get key information
     *
     * Extract the information from the key and return them in an array:
     *  - fingerprint   : fingerprint of the key, string(40)
     *  - bits          : key size / curve length (int)
     *  - type          : algorithm used by the key (RSA, ELGAMAL, DSA, EdDSA, etc..)
     *  - key_id        : key id, string(8)
     *  - key_created   : date of creation of the key, timestamp
     *  - uid           : user id of the key following gpg standard (usually name surname (comment) <email>), string
     *  - expires       : expiration date or empty if no expiration date, timestamp
     *  - revoked       : if the key has been revoked
     *  - user_ids      : array of name, email, comment
     *  - sub_keys      : array of fingerprint, key_id
     *  - public_key_packet_counts : number of public key packets
     *  - secret_key_packet_counts : number of secret key packets
     *  - armored       : the armored key block for future use
     *
     * Important note : this function is using OpenPGP-PHP library instead of php-gnupg to pre-validate the key.
     * And get more data out of the key packages
     *
     * @param string $armoredKey the ASCII armored key block
     * @return array as described above
     */
    public function getKeyInfo(string $armoredKey): array
    {
        $results = $this->getEmptyKeyInfoDto();

        $keyUnarmored = $this->unarmor($armoredKey, $this->getGpgMarker($armoredKey));
        if ($keyUnarmored === false) {
            throw new CakeException(__('Invalid key. No OpenPGP public key package found.'));
        }

        // Get the message.
        $msg = @\OpenPGP_Message::parse($keyUnarmored); // phpcs:ignore
        if (empty($msg->packets)) {
            throw new CakeException(__('Invalid key. No OpenPGP public key package found.'));
        }

        // Parse public key.
        $publicKey = @\OpenPGP_PublicKeyPacket::parse($keyUnarmored); // phpcs:ignore
        if ($publicKey === null) {
            throw new CakeException(__('Invalid key. No OpenPGP public key package found.'));
        }

        // Get first packet for public key information.
        $publicKeyPacket = $msg->packets[0];
        if (!$publicKeyPacket instanceof \OpenPGP_PublicKeyPacket) {
            throw new CakeException(__('Invalid key. No OpenPGP public key package found.'));
        }
        $results['fingerprint'] = @$publicKeyPacket->fingerprint(); // phpcs:ignore
        // will throw an exception if fingerprint is not readable or valid
        $results['key_id'] = self::fingerprintToKeyId($results['fingerprint']);
        $results['type'] = $this->getKeyAlgorithm($publicKeyPacket);
        $results['bits'] = $this->getKeySize($publicKeyPacket);
        $results['expires'] = @$publicKeyPacket->expires($msg); // phpcs:ignore
        $results['key_created'] = $publicKeyPacket->timestamp;

        foreach ($msg->packets as $packet) {
            // Get uid from first user id packet data we find (legacy way)
            // Save the details for future use (this is the way)
            if ($packet instanceof \OpenPGP_UserIDPacket) {
                if (!isset($results['uid'])) {
                    $results['uid'] = $packet->data;
                }
                $results['user_ids'][] = [
                    'name' => $packet->name ?? null,
                    'email' => $packet->email ?? null,
                    'comment' => $packet->comment ?? null,
                ];
            }

            // Look for key revocation signature packet
            if (!$results['revoked'] && $packet instanceof \OpenPGP_SignaturePacket) {
                if (self::containsRevocationSignature($packet, $results['key_id'])) {
                    $results['revoked'] = true;
                }
            }

            // Look into public key sub packet to find subkey id / fingerprint
            // Will be useful to validate recipients of asymetric encrypted messages
            if (($packet instanceof \OpenPGP_PublicSubkeyPacket) || ($packet instanceof \OpenPGP_SecretSubkeyPacket)) {
                if ($packet->fingerprint !== null) {
                    $results['sub_keys'][] = [
                        'key_id' => self::fingerprintToKeyId($packet->fingerprint),
                        'fingerprint' => $packet->fingerprint,
                    ];
                }
            }

            // Some OpenPGP message can contain multiple keys, causing some known issues
            // see. https://community.passbolt.com/t/ios-mobile-app-sign-in-fails/4793/7
            $className = get_class($packet);
            if ($className === \OpenPGP_PublicKeyPacket::class) {
                $results['public_key_packet_counts']++;
            } elseif ($className === \OpenPGP_SecretKeyPacket::class) {
                $results['secret_key_packet_counts']++;
            }
        }

        // A user id is mandatory
        if (!isset($results['uid'])) {
            throw new CakeException(__('Invalid key. No user ID found.'));
        }

        $results['armored'] = $armoredKey;

        return $results;
    }

    /**
     * @return array
     */
    private function getEmptyKeyInfoDto(): array
    {
        return [
            'key_id' => null,
            'fingerprint' => null,
            'key_created' => null,
            'expires' => null,
            'bits' => null,
            'type' => null,
            'uid' => null,
            'revoked' => false,
            'sub_keys' => [],
            'user_ids' => [],
            'public_key_packet_counts' => 0,
            'secret_key_packet_counts' => 0,
        ];
    }

    /**
     * @param \OpenPGP_PublicKeyPacket $firstKeyPacket first packet
     * @return string
     */
    private function getKeyAlgorithm(\OpenPGP_PublicKeyPacket $firstKeyPacket): string
    {
        if (!isset(\OpenPGP_PublicKeyPacket::$key_fields[$firstKeyPacket->algorithm])) {
            throw new CakeException(__('Unsupported algorithm.'));
        }

        return \OpenPGP_PublicKeyPacket::$algorithms[$firstKeyPacket->algorithm];
    }

    /**
     * Return public key size / length
     *
     * @param \OpenPGP_PublicKeyPacket $keyPacket key packet
     * @return int key size/length ex. 2048, 0 if undefined
     */
    private function getKeySize(\OpenPGP_PublicKeyPacket $keyPacket): int
    {
        $algorithm = \OpenPGP_PublicKeyPacket::$algorithms[$keyPacket->algorithm];
        $keyFirstElt = \OpenPGP_PublicKeyPacket::$key_fields[$keyPacket->algorithm][0];

        if (isset($keyPacket->key[$keyFirstElt])) {
            $size = $keyPacket->key[$keyFirstElt];
            switch ($algorithm) {
                case 'DSA':
                case 'RSA':
                    return \OpenPGP::bitlength($size);
                case 'ECDSA':
                case 'EdDSA':
                    $oid = strtoupper(bin2hex($size));
                    switch ($oid) {
                        // https://datatracker.ietf.org/doc/html/draft-ietf-openpgp-rfc4880bis-10#section-9.2
                        case '2A8648CE3D030107': // NIST P-256
                        case '2B06010401DA470F01': // Ed25519
                        case '2B060104019755010501': // Curve25519
                        case '2B2403030208010107': // brainpoolP256r1
                            return 256;
                        case '2B240303020801010B': // brainpoolP384r1
                        case '2B81040022': // NIST P-384
                            return 384;
                        case '2B240303020801010D': // brainpoolP512r1
                            return 512;
                        case '2B81040023': // NIST P-521
                            return 521;
                    }
                    break;
            }
        }

        return 0;
    }

    /**
     * Does the signature packet contain a key revocation signature
     * Look for the key revocation (not sub key, or user id, or signature revocation)
     *
     * @param \OpenPGP_SignaturePacket $packet key packet
     * @param string $longKeyId 16 chars
     * @return bool
     */
    public static function containsRevocationSignature(\OpenPGP_SignaturePacket $packet, string $longKeyId): bool
    {
        // https://datatracker.ietf.org/doc/html/draft-ietf-openpgp-rfc4880bis-10#section-5.2.1
        // 32 = 0x20 Key revocation signature.
        if ($packet->signature_type === 32) {
            $acceptUnhashedRevocationIssuerSubPacket =
                Configure::read('passbolt.gpg.security.acceptRevokedKeyUnhashedIssuerSubPacket');
            $revocationPacket = false;
            $revocationKeyId = false;
            foreach ($packet->hashed_subpackets as $s) {
                if (!$revocationPacket && $s instanceof \OpenPGP_SignaturePacket_ReasonforRevocationPacket) {
                    $revocationPacket = true;
                }
                if (!$revocationKeyId && $s instanceof \OpenPGP_SignaturePacket_IssuerPacket) {
                    $revocationKeyId = ($s->data === $longKeyId);
                }
            }
            foreach ($packet->unhashed_subpackets as $s) {
                if ($s instanceof \OpenPGP_SignaturePacket_IssuerPacket) {
                    $revocationKeyIdFound = ($s->data === $longKeyId);
                    if ($revocationKeyIdFound && $acceptUnhashedRevocationIssuerSubPacket) {
                        $revocationKeyId = true;
                    } else {
                        /*
                         * By default, new signature Issuer, Issuer Fingerprint, and Embedded Signature subpackets
                         * generated by openpgpjs >= 5.5 have been moved to hashed subpackets.
                         */
                        $message = 'Revocation issuer in unhashed sub-packets ignored due to security settings. To '
                            . 'recognize it, change the passbolt gpg security setting '
                            . 'passbolt.gpg.security.acceptRevokedKeyUnhashedIssuerSubPacket';
                        Log::warning($message);
                    }
                    break;
                }
            }

            return $revocationPacket && $revocationKeyId;
        }

        return false;
    }
}
