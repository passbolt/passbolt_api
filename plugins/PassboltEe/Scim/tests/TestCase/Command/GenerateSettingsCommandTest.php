<?php
declare(strict_types=1);

namespace Passbolt\Scim\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Passbolt\Scim\Command\GenerateScimSettingsCommand;

/**
 * Passbolt\Scim\Command\GenerateSettingsCommand Test Case
 *
 * @uses \Passbolt\Scim\Command\GenerateScimSettingsCommand
 */
class GenerateSettingsCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * Test buildOptionParser method
     *
     * @return void
     * @uses \Passbolt\Scim\Command\GenerateScimSettingsCommand::buildOptionParser()
     */
    public function testBuildOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     * @uses \Passbolt\Scim\Command\GenerateScimSettingsCommand::execute()
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
