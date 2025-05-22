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

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * @uses \Passbolt\DirectorySync\Command\UsersCommand
 */
class UsersCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * Test the help option
     *
     * @return void
     */
    public function testDirectoryUsersCommandHelp(): void
    {
        $this->exec('directory_sync users -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Sync users');
        $this->assertOutputContains('--dry-run');
        $this->assertOutputContains("Don't save the changes");
        $this->assertOutputContains('--persist');
        $this->assertOutputContains("Persist data, otherwise it won't save the changes");
    }

    public function testDirectoryUsersCommand_DryRun_WithEmptyData(): void
    {
        UserFactory::make()->admin()->active()->persist();
        $this->exec('directory_sync users --dry-run');

        $this->assertExitSuccess();
        $this->assertOutputContains('Users');
        $this->assertOutputContains('Created:');
        $this->assertOutputContains('<success>[success] No new item to create.</success>');
        $this->assertOutputContains('Updated:');
        $this->assertOutputContains('<success>[success] No new item to update</success>');
        $this->assertOutputContains('Deleted:');
        $this->assertOutputContains('<success>[success] No new item to delete</success>');
    }

    public function testDirectoryUsersCommand_Persist_ErrorValidationIssues(): void
    {
        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
        $admin = UserFactory::make()->admin()->active()->persist();
        // Populate the settings for the test
        $uac = $this->makeUac($admin);
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $this->exec('directory_sync users --persist');

        $this->assertExitSuccess();
        $this->assertOutputContains('To ignore this error in the next sync please run');
        $this->assertOutputContains('./bin/cake directory_sync ignore_create --id= --model=Users');
        $this->assertErrorContains('The user user1@passbolt.com could not be added because of data validation issues');
        $this->assertErrorContains('Role id: This field cannot be left empty');
        $this->assertErrorContains('The user user2@passbolt.com could not be added because of data validation issues');
        $this->assertErrorContains('The user user3@passbolt.com could not be added because of data validation issues');
        $this->assertErrorContains('The user user4@passbolt.com could not be added because of data validation issues');
        $this->assertErrorContains('The user undefined could not be added because of data validation issues');
        $this->assertErrorContains('Username: A username is required');
    }
}
