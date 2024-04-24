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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Command;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;

/**
 * @uses \Passbolt\JwtAuthentication\Command\CreateJwtKeysCommand
 */
class CreateJwtKeysCommandTest extends AppTestCase
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
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
    }

    /**
     * Basic help test
     */
    public function testCreateJwtKeysCommandHelp()
    {
        $this->exec('passbolt create_jwt_keys -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Create a JWT key pair.');
        $this->assertOutputContains('cake passbolt create_jwt_keys');
    }

    public function testCreateJwtKeysCommandNoForce()
    {
        $this->deleteFileIfExists(CONFIG . 'jwt' . DS . 'jwt.key');
        $this->deleteFileIfExists(CONFIG . 'jwt' . DS . 'jwt.perm');

        // Will succeed if no keys exist
        $this->exec('passbolt create_jwt_keys -q');
        $this->assertExitSuccess();

        // Will fail if keys already exist
        $this->exec('passbolt create_jwt_keys -q');
        $this->assertExitError();
    }

    public function testCreateJwtKeysCommandForce()
    {
        $this->exec('passbolt create_jwt_keys -q -f');
        $this->assertExitSuccess();
    }

    private function deleteFileIfExists(string $file): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
