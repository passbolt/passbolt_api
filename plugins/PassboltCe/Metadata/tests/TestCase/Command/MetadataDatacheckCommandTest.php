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
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
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

    public function testDatacheckCommand_Success_CheckV5Resources(): void
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
            ->v5Fields(true, [
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
}
