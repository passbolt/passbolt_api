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
namespace App\Test\TestCase\Command;

use App\Service\Command\ProcessUserService;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Exception\CakeException;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\Routing\Router;

class SslHealthcheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use HealthcheckRequestTestTrait;
    use PassboltCommandTestTrait;
    use HttpClientTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mockService(ProcessUserService::class, function () {
            $stub = $this->getMockBuilder(ProcessUserService::class)
                ->onlyMethods(['getName'])
                ->getMock();
            $stub->method('getName')->willReturn('www-data');

            return $stub;
        });
    }

    public function testSslHealthcheckCommand_SSL_Happy_Path()
    {
        $this->mockClientGet(
            Router::url('/healthcheck/status.json', true),
            $this->newClientResponse(200, [], json_encode(['body' => 'OK']))
        );

        $this->exec('passbolt healthcheck --ssl');

        $this->assertExitSuccess();
        $this->assertOutputContains('<success>[PASS]</success> SSL peer certificate validates.');
        $this->assertOutputContains('<success>[PASS]</success> Hostname is matching in SSL certificate.');
        $this->assertOutputContains('<success>[PASS]</success> Not using a self-signed certificate.');
    }

    public function testSslHealthcheckCommand_SSL_Unhappy_Path()
    {
        $exceptionMessage = 'Foo';

        $stub = $this->getMockBuilder(Client::class)->onlyMethods(['get'])->getMock();
        $stub->method('get')->willThrowException(new CakeException($exceptionMessage));
        $stub->expects($this->exactly(3))->method('get');
        $this->mockService('sslHealthcheckClient', function () use ($stub) {
            return $stub;
        });

        $this->exec('passbolt healthcheck --ssl');

        $this->assertExitSuccess();
        $this->assertOutputContains('<warning>[WARN] SSL peer certificate does not validate.</warning>');
        $this->assertOutputContains('<info>[HELP]</info> ' . $exceptionMessage);
        $this->assertOutputContains('<warning>[WARN] Hostname does not match when validating certificates.</warning>');
        $this->assertOutputContains('<warning>[WARN] Using a self-signed certificate.</warning>');
        $this->assertOutputContains('<info>[HELP]</info> Check https://help.passbolt.com/faq/hosting/troubleshoot-ssl');
    }
}
