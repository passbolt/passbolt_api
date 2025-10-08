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
 * @since         5.1.0
 */
namespace Passbolt\Metadata\Test\TestCase\Command;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * App\Command\MigrateCommand Test Case
 *
 * @covers \App\Command\DatacheckCommand
 */
class MetadataDatacheckCommandTest extends AppTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use GpgMetadataKeysTestTrait;

    public function testMetadataDatacheckCommand_Success_CanValidateV5Resources(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // v4 resources
        $v4Resource1 = ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();
        $v4Resource2 = ResourceFactory::make()->withPermissionsFor([$admin])->persist();
        // v5 resource
        $v5Resource = ResourceFactory::make()
            ->v5Fields(false, [
                'metadata_key_id' => $admin->gpgkey->id,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
            ])
            ->withPermissionsFor([$admin])
            ->persist();

        $this->exec('passbolt datacheck');

        $this->assertExitSuccess();
        $this->assertOutputContains('<success>[PASS]</success> Data integrity for Resources.');
        $this->assertOutputContains("Validation success for resource {$v5Resource->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v4Resource1->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v4Resource2->get('id')}");
    }

    public function testMetadataDatacheckCommand_Success_IsMetadataKeyExistAndActive(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // v4 resources
        $v4Resource1 = ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();
        $v4Resource2 = ResourceFactory::make()->withPermissionsFor([$admin])->persist();
        // v5 resource
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $v5ResourceWithMetadataKeyPresent = ResourceFactory::make()
            ->v5Fields(true, [
                'metadata_key_id' => $metadataKey->get('id'),
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
            ])
            ->withPermissionsFor([$admin])
            ->persist();
        $v5ResourceWithMetadataKeyMissing = ResourceFactory::make()
            ->v5Fields(true, [
                'metadata_key_id' => UuidFactory::uuid(),
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
            ])
            ->withPermissionsFor([$admin])
            ->persist();

        $this->exec('passbolt datacheck');

        $this->assertExitSuccess();
        $this->assertOutputContains('<error>[FAIL] Data integrity for Resources.</error>');
        $this->assertOutputContains("Validation success for resource {$v4Resource1->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v4Resource2->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v5ResourceWithMetadataKeyPresent->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v5ResourceWithMetadataKeyMissing->get('id')}");
        $this->assertOutputContains("Metadata key exist and present for resource {$v5ResourceWithMetadataKeyPresent->get('id')}");
        $this->assertOutputContains('<error>[FAIL] Is metadata key exist and active: 1/2</error>');
        $this->assertOutputContains("Metadata key does not exists or soft-deleted for resource {$v5ResourceWithMetadataKeyMissing->get('id')}");
    }

    public function testMetadataDatacheckCommand_Success_MetadataKeysWithoutMetadataPrivateKeys(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // v4 resource
        $v4Resource = ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();
        // v5 resource
        $metadataKeyWithPrivateKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $metadataKeyWithoutPrivateKey = MetadataKeyFactory::make()->persist();
        MetadataKeyFactory::make()->deleted()->persist(); // make sure deleted metadata keys are omitted
        $v5Resource = ResourceFactory::make()
            ->v5Fields(true, [
                'metadata_key_id' => $metadataKeyWithPrivateKey->get('id'),
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
            ])
            ->withPermissionsFor([$admin])
            ->persist();

        $this->exec('passbolt datacheck');

        $this->assertExitSuccess();
        $this->assertOutputContains('<error>[FAIL] Data integrity for MetadataKeys.</error>');
        $this->assertOutputContains("Validation success for resource {$v4Resource->get('id')}");
        $this->assertOutputContains("Validation success for resource {$v5Resource->get('id')}");
        $this->assertOutputContains('<error>[FAIL] Check metadata private keys present: 1/2</error>');
        $this->assertOutputContains("Metadata keys present for metadata key {$metadataKeyWithPrivateKey->get('id')}");
        $this->assertOutputContains("No metadata private keys found for metadata key {$metadataKeyWithoutPrivateKey->get('id')}");
    }
}
