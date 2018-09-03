<?php
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
namespace Passbolt\DirectorySync\Test\TestCase\Shell;

use Cake\Console\Shell;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;

class DirectorySyncShellTest extends DirectorySyncConsoleIntegrationTestCase
{
    /**
     * setup method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->setAppNamespace('Passbolt');
    }

    /**
     * Assert the help output.
     *
     * @param string $output The output to check.
     * @return void
     */
    protected function assertCommandList()
    {
        $this->assertOutputContains('all', 'Synchronize users and groups');
    }

    /**
     * Test the command listing fallback when no commands are set
     *
     * @return void
     */
    public function testMainNoCommandsFallback()
    {
        $this->exec('directory_sync --help');
        $this->assertExitCode(Shell::CODE_SUCCESS);
        $this->assertCommandList();
    }
}
