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
 * @since         4.7.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Command;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Passbolt\DirectorySync\DirectorySyncPlugin;
use Passbolt\DirectorySync\Middleware\DirectorySyncEndpointsSecurityMiddleware;

class DirectorySyncHealthcheckCommandTest extends AppTestCase
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
        $this->mockProcessUserService('www-data');
        $this->enableFeaturePlugin(DirectorySyncPlugin::class);
    }

    public function testDirectorySyncHealthcheckCommand_Help(): void
    {
        $this->exec('passbolt healthcheck -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('--directorySync');
        $this->assertOutputContains('Run Directory Sync tests only');
    }

    public function testDirectorySyncHealthcheckCommand_DefaultEndpointsEnabled(): void
    {
        $this->exec('passbolt healthcheck --directorySync');

        $this->assertExitSuccess();
        $this->assertOutputContains('The endpoints for updating the users directory configurations are enabled');
        $this->assertOutputContains('It is recommended to disable endpoints for updating the users directory configurations');
        $this->assertOutputContains('Set the PASSBOLT_SECURITY_DIRECTORY_SYNC_ENDPOINTS_DISABLED environment variable to true');
        $this->assertOutputContains('Or set passbolt.security.directorySync.endpointsDisabled to true in ' . CONFIG . 'passbolt.php');
    }

    public function testDirectorySyncHealthcheckCommand_EndpointsDisabled(): void
    {
        Configure::write(DirectorySyncEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);

        $this->exec('passbolt healthcheck --directorySync');

        $this->assertExitSuccess();
        $this->assertOutputContains('The endpoints for updating the users directory configurations are disabled');
        $this->assertOutputNotContains('It is recommended to disable endpoints for updating the users directory configurations');
        $this->assertOutputNotContains('Set the PASSBOLT_SECURITY_DIRECTORY_SYNC_ENDPOINTS_DISABLED environment variable to true');
        $this->assertOutputNotContains('Or set ' . DirectorySyncEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY . ' to true in ' . CONFIG . 'passbolt.php');
    }
}
