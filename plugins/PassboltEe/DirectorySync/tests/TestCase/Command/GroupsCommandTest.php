<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Command;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * @uses \Passbolt\DirectorySync\Command\GroupsCommand
 */
class GroupsCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
    }

    /**
     * Test the help option
     *
     * @return void
     */
    public function testDirectoryGroupsCommandHelp(): void
    {
        $this->exec('directory_sync groups -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Sync groups');
        $this->assertOutputContains('--dry-run');
        $this->assertOutputContains("Don't save the changes");
        $this->assertOutputContains('--persist');
        $this->assertOutputContains("Persist data, otherwise it won't save the changes");
    }

    public function testDirectoryGroupsCommand_DryRun(): void
    {
        UserFactory::make()->admin()->active()->persist();
        $this->exec('directory_sync groups --dry-run');

        $this->assertExitSuccess();
        $this->assertOutputContains('Groups');
        $this->assertOutputContains('Created:');
        $this->assertOutputContains('<success>[success] The group Administration was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group Managers was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group C Level was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group Developers was successfully added to passbolt.</success>');
        $this->assertOutputContains('Updated:');
        $this->assertOutputContains('<success>[success] No new item to update</success>');
        $this->assertOutputContains('Deleted:');
        $this->assertOutputContains('<success>[success] No new item to delete</success>');
    }

    public function testDirectoryGroupsCommand_Persist(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        // Populate the settings for the test
        $uac = $this->makeUac($admin);
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $this->exec('directory_sync groups --persist');

        $this->assertExitSuccess();
        $this->assertOutputContains('Groups');
        $this->assertOutputContains('Created:');
        $this->assertOutputContains('<success>[success] The group Administration was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group Managers was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group C Level was successfully added to passbolt.</success>');
        $this->assertOutputContains('<success>[success] The group Developers was successfully added to passbolt.</success>');
        $this->assertOutputContains('Updated:');
        $this->assertOutputContains('<success>[success] No new item to update</success>');
        $this->assertOutputContains('Deleted:');
        $this->assertOutputContains('<success>[success] No new item to delete</success>');
        // make sure groups are saved into DB
        $this->assertSame(4, GroupFactory::find()->all()->count());
    }
}
