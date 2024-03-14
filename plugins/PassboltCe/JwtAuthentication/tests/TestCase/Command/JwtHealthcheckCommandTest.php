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
namespace Passbolt\JwtAuthentication\Test\TestCase\Command;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtAbstractService;

class JwtHealthcheckCommandTest extends AppTestCase
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
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
    }

    public function testHealthcheckCommand_Jwt_Plugin_Disabled()
    {
        $this->disableFeaturePlugin(JwtAuthenticationPlugin::class);
        $this->exec('passbolt healthcheck --jwt');
        $this->assertExitSuccess();
        $this->assertOutputContains('<warning>[WARN] The JWT Authentication plugin is disabled.</warning>');
        $this->assertOutputContains('<info>[HELP]</info> Set the environment variable PASSBOLT_PLUGINS_JWT_AUTHENTICATION_ENABLED to true');
        $this->assertOutputContains('No error found. Nice one sparky!');
    }

    public function testHealthcheckCommand_Jwt_Valid()
    {
        $this->exec('passbolt healthcheck --jwt');
        $this->assertExitSuccess();
        $this->assertOutputContains('<success>[PASS]</success> The JWT Authentication plugin is enabled.');
        if (is_writable(JwtAbstractService::JWT_CONFIG_DIR)) {
            $this->assertOutputContains('<error>[FAIL] The ' . JwtAbstractService::JWT_CONFIG_DIR . ' directory should not be writable.</error>');
        } else {
            $this->assertOutputContains('<success>[PASS]</success> The ' . JwtAbstractService::JWT_CONFIG_DIR . ' directory is not writable.');
        }
        $this->assertOutputContains('<success>[PASS]</success> A valid JWT key pair was found.');
    }
}
