<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.5.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Passbolt\DirectorySync\Test\TestCase\Shell;
use Cake\Console\Shell;
use Cake\Core\Plugin;
use Cake\TestSuite\ConsoleIntegrationTestCase;

class DirectorySyncShellTest extends ConsoleIntegrationTestCase
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