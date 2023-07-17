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

use Passbolt\DirectorySync\Command\DirectorySyncCommand;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;

/**
 * @uses \Passbolt\DirectorySync\Command\DirectorySyncCommand
 */
class DirectorySyncCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * Test the help option
     *
     * @return void
     */
    public function testDirectorySyncCommandHelp(): void
    {
        $this->exec('directory_sync -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('The directory shell offer synchronizations tasks from the CLI.');
        $this->assertOutputContains('cake directory_sync');
    }

    public function testDirectorySyncCommand()
    {
        $this->exec('directory_sync');
        $this->assertExitSuccess();
    }

    public function testDirectorySyncCommandAsAdmin()
    {
        DirectorySyncCommand::$isUserRoot = true;
        $this->exec('directory_sync');
        $this->assertExitError();
    }
}
