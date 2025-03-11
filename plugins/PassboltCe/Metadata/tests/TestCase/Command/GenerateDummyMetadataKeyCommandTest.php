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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;

/**
 * @covers \Passbolt\Metadata\Command\MigrateAllItemsCommand
 */
class GenerateDummyMetadataKeyCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGenerateDummyMetadataKeyCommand_Help()
    {
        $this->exec('passbolt metadata generate_dummy_metadata_key -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Generate a metadata private/public key pair.');
    }

    public function testGenerateDummyMetadataKeyCommand_No_Admin(): void
    {
        $this->exec('passbolt metadata generate_dummy_metadata_key');
        $this->assertErrorContains('No admin were found in the database.');
        $this->assertExitError();
    }

    public function testGenerateDummyMetadataKeyCommand_Success(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->exec('passbolt metadata generate_dummy_metadata_key');
        $this->assertExitSuccess();
        $metadataKey = MetadataKeyFactory::firstOrFail();
        $this->assertSame($admin->get('id'), $metadataKey->created_by);
        $this->assertSame($admin->get('id'), $metadataKey->modified_by);
    }
}
