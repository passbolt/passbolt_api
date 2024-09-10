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

use App\Model\Entity\Resource;
use App\Utility\OpenPGP\OpenPGPBackendFactory;

/**
 * Helper methods with common assertions.
 */
trait MigrateResourcesTestTrait
{
    /**
     * Assert decrypted metadata content for given resource.
     *
     * @param Resource $resource Resource entity.
     * @param array $metadata Metadata array.
     * @return void
     */
    private function assertMetadataAgainstResource(Resource $resource, array $metadata): void
    {
        $this->assertSame($resource->name, $metadata['name']);
        $this->assertSame($resource->username, $metadata['username']);
        $this->assertSame($resource->description, $metadata['description']);
        $this->assertSame($resource->uri, $metadata['uris'][0]);
        $this->assertSame('PASSBOLT_RESOURCE_METADATA', $metadata['object_type']);
    }

    /**
     * Asserts if entity contains valid data.
     *
     * @param Resource $resource Resource entity.
     * @return void
     */
    private function assertUpdatedResource(Resource $resource): void
    {
        $this->assertNull($resource->name);
        $this->assertNull($resource->username);
        $this->assertNull($resource->uri);
        $this->assertNull($resource->description);
        // Assertions for metadata
        $metadata = $resource->get('metadata');
        $this->assertNotNull($metadata);
    }

    /**
     * @param string $ciphertext Message to decrypt.
     * @param array $keyInfo Key information - fingerprint, passphrase, armored_key(private key).
     * @return string
     */
    private function decrypt(string $ciphertext, array $keyInfo): string
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg->clearKeys();

        $fingerprint = $keyInfo['fingerprint'];
        $passphrase = $keyInfo['passphrase'];

        try {
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            $gpg->importKeyIntoKeyring($keyInfo['armored_key']);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        }

        return $gpg->decrypt($ciphertext);
    }
}
