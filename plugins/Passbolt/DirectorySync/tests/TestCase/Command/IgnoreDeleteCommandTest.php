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

use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;

/**
 * @uses \Passbolt\DirectorySync\Command\IgnoreDeleteCommand
 */
class IgnoreDeleteCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * Test the help option
     *
     * @return void
     */
    public function testIgnoreDeleteCommandHelp(): void
    {
        $this->exec('directory_sync ignore_delete -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Stop ignoring a record.');
    }
}