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
 * @since         4.8.0
 */
namespace App\Test\TestCase\Service\Healthcheck\Application;

use App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck;
use App\Service\Network\SocketService;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\Network\Exception\SocketException;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck
 */
class LatestVersionApplicationHealthcheckTest extends TestCase
{
    public function testLatestVersionApplicationHealthcheck_Success(): void
    {
        $client = new Client();
        $socketService = new SocketService();
        // Set version to high on purpose so that we don't have to change this each time when we version bump
        Configure::write('passbolt.version', '5.0.0');

        $service = new LatestVersionApplicationHealthcheck($client, $socketService);
        $service->check();

        $this->assertTrue($service->isPassed());
        $this->assertTextNotEquals('undefined', $service->getRemoteVersion());
    }

    public function testLatestVersionApplicationHealthcheck_Error_GithubNotReachable(): void
    {
        $client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $response = new Response([], json_encode(['tag_name' => '4.7.0']));
        $client->method('get')->willReturn($response);
        // mock socket service
        $socketService = $this->getMockBuilder(SocketService::class)->getMock();
        $socketService
            ->method('canConnect')
            ->willThrowException(new SocketException('Unable to connect'));

        $service = new LatestVersionApplicationHealthcheck($client, $socketService);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame('undefined', $service->getRemoteVersion());
        $expectedFailureMessage = 'Could not connect to passbolt repository to check versions. It is not possible check if your version is up to date.';
        $this->assertSame($expectedFailureMessage, $service->getFailureMessage());
    }
}
