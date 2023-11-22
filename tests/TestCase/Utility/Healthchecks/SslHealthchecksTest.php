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
 * @since         4.5.0
 */
namespace App\Test\TestCase\Utility\Healthchecks;

use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Utility\Healthchecks\SslHealthchecks;
use Cake\TestSuite\TestCase;

class SslHealthchecksTest extends TestCase
{
    use HealthcheckRequestTestTrait;

    public function testSslHealthchecks_Success()
    {
        $client = $this->getMockedHealthcheckStatusRequest();
        $service = new SslHealthchecks($client);
        $result = $service->all();
        $expected = ['ssl' => [
            'peerValid' => true,
            'hostValid' => true,
            'notSelfSigned' => true,
        ]];
        $this->assertSame($expected, $result);
    }

    public function testSslHealthchecks_Fail()
    {
        $client = $this->getMockedHealthcheckStatusRequest(400);
        $service = new SslHealthchecks($client);
        $result = $service->all();
        $expected = ['ssl' => [
            'peerValid' => false,
            'hostValid' => false,
            'notSelfSigned' => false,
        ]];
        $this->assertSame($expected, $result);
    }
}
