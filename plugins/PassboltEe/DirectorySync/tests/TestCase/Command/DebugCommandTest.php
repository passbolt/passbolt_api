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
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * @uses \Passbolt\DirectorySync\Command\DebugCommand
 */
class DebugCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * Test the help option
     *
     * @return void
     */
    public function testDirectoryDebugCommandHelp(): void
    {
        $this->exec('directory_sync debug -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Debug configuration helper');
    }

    /**
     * The aim if this test so far is to provide a minimum test coverage
     * of the present command. Further tests will be required to
     * cover the various cases of the business logic.
     *
     * @return void
     */
    public function testDirectoryDebugCommand(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($admin);
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $this->useCommandRunner();
        $this->exec('directory_sync debug');
        $this->assertExitError();
        $this->assertOutputContains('<error>Can\'t contact LDAP server</error>');
    }
}
