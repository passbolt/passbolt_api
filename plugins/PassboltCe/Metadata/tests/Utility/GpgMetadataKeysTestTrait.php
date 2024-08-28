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
namespace Passbolt\Metadata\Test\Utility;

/**
 * A helper to get test GPG keys, encrypted messages, etc.
 */
trait GpgMetadataKeysTestTrait
{
    /**
     * Returns info related to Maki's key.
     *
     * @return array
     */
    public function getMakiKeyInfo(): array
    {
        return [
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'maki_public.key'), // ecc, curve25519
            'fingerprint' => '3EED5E73EA34C95198A904067B28D501637D5102',
            'email' => 'maki@passbolt.com',
        ];
    }

    /**
     * Returns info related to Metadata server key.
     *
     * @return array
     */
    public function getServerKeyInfo(): array
    {
        return [
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'server_public.key'), // ecc, curve25519
            'fingerprint' => '697179F80870413657DFE70BEB200F2DA0AC5BD3',
            'email' => 'metadata_server_key@passbolt.test',
        ];
    }

    /**
     * Message encrypted with Maki (maki@passbolt.com) and Server Key - two recipients.
     * (signed with server/unsecure public key)
     *
     * @return false|string
     */
    public function getEncryptedMessageForMakiAndServerKey()
    {
        // Decrypted message: I'm super strong private key
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_private_key.msg');
    }
}
