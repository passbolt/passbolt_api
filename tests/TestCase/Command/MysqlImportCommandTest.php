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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Test\Lib\Utility\PassboltCommandTestTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class MysqlImportCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Basic help test
     */
    public function testMysqlImportCommandHelp()
    {
        $this->exec('passbolt mysql_import -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Utility to import a mysql database backups.');
        $this->assertOutputContains('cake passbolt mysql_import');
    }

    /**
     * @Given A backup has been created creating a resource
     * @When I run "passbolt mysql_import"
     * @Then the resource must have been imported.
     */
    public function testMysqlImportCommandOnDump()
    {
        // Create a file with a simple sql command
        $dir = $this->makeCommandTempDir();
        $fileName = 'dummy_dump.sql';
        $avatarId = UuidFactory::uuid();
        $profileId = UuidFactory::uuid();
        $sql = "INSERT INTO `avatars` (id, profile_id) VALUES ('$avatarId', '$profileId');";
        file_put_contents($dir . DS . $fileName, $sql);

        // Import that file
        $this->exec("passbolt mysql_import --file $fileName --dir $dir -d test");
        $this->assertExitSuccess();
        $this->assertOutputContains('Success: SQL file imported');

        // Assert that the avatar was created
        TableRegistry::getTableLocator()->get('Avatars')->find()->firstOrFail();
    }

    /**
     * Various scenarios when specifying a file name or not.
     * This test covers IMO all the cases where $dir is specified.
     */
    public function testMysqlImportCommandWrongDataSource()
    {
        // Create a file with a simple sql command
        $dir = $this->makeCommandTempDir();
        $fileName = 'dummy_dump.sql';
        $sql = 'THIS IS NOT SQL, AND WILL THROW AN ERROR!!!';
        file_put_contents($dir . DS . $fileName, $sql);

        // Import
        $this->exec("passbolt mysql_import --file $fileName --dir $dir");
        $this->assertOutputContains('Error: Something went wrong when importing the SQL file');

        // Import with a wrong datasource
        $this->exec("passbolt mysql_import -file $fileName -dir $dir -d wrong_data_source");
        $this->assertOutputContains('Error: Something went wrong when importing the SQL file');
    }
}
