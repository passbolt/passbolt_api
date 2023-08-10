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
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;

/**
 * @uses \Passbolt\DirectorySync\Command\AllCommand
 */
class AllCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * Test the help option
     *
     * @return void
     */
    public function testDirectoryAllCommandHelp(): void
    {
        $this->useCommandRunner();
        $this->exec('directory_sync all -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Directory Sync');
    }

    public function testDirectoryAllCommandWithExistingAdmin(): void
    {
        UserFactory::make()->admin()->persist();

        $this->useCommandRunner();
        $this->exec('directory_sync all --persist');
        $this->assertExitSuccess();
    }

    public function testDirectoryAllCommandWithExistingAdminNoConfigChecked(): void
    {
        UserFactory::make()->admin()->persist();

        $this->useCommandRunner();
        $this->exec('directory_sync all');
        $this->assertExitSuccess();
        $this->assertErrorContains('check config and pass option --persist');
    }
}
