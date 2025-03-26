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
 * @since         4.11.1
 */
namespace App\Test\TestCase\Service\Healthcheck\Core;

use App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck
 */
class FullBaseUrlCoreHealthcheckTest extends TestCase
{
    /**
     * @dataProvider validFullBaseUrlCoreHealthcheckStringValuesProvider
     */
    public function testFullBaseUrlCoreHealthcheck_Pass(string $url, bool $expectedResult): void
    {
        Configure::write('App.fullBaseUrl', $url);
        $result = (new FullBaseUrlCoreHealthcheck())->check()->isPassed();
        $this->assertSame($expectedResult, $result);
    }

    public function validFullBaseUrlCoreHealthcheckStringValuesProvider(): array
    {
        return [
            [
                'url' => 'https://cloud.passbolt.com/workspace',
                'expected result' => true,
            ],
            [
                'url' => 'https://passbolt.com',
                'expected result' => true,
            ],
            [
                'url' => 'https://passbolt.cloud',
                'expected result' => true,
            ],
            [
                'url' => 'https://passbolt',
                'expected result' => true,
            ],
            [
                'url' => 'https://localhost',
                'expected result' => true,
            ],
            [
                'url' => 'https://localhost:8443',
                'expected result' => true,
            ],
            [
                'url' => 'http://cloud.passbolt.com/workspace',
                'expected result' => true,
            ],
            [
                'url' => 'http://passbolt.com',
                'expected result' => true,
            ],
            [
                'url' => 'http://passbolt.cloud',
                'expected result' => true,
            ],
            [
                'url' => 'http://passbolt',
                'expected result' => true,
            ],
            [
                'url' => 'http://127.0.0.1',
                'expected result' => true,
            ],
            [
                'url' => 'http://localhost',
                'expected result' => true,
            ],
            [
                'url' => 'http://localhost:8080',
                'expected result' => true,
            ],
            [
                'url' => '🔥',
                'expected result' => true,
            ],
        ];
    }

    public function testFullBaseUrlCoreHealthcheck_Pass_Array(): void
    {
        Configure::write('App.fullBaseUrl', []);
        $healthcheck = new FullBaseUrlCoreHealthcheck();
        $result = $healthcheck->check()->isPassed();
        $this->assertTrue($result);
        $this->assertStringContainsString('Full base url is set to "array"', $healthcheck->getSuccessMessage());
    }

    public function testFullBaseUrlCoreHealthcheck_Fail_NotSet(): void
    {
        Configure::write('App.fullBaseUrl', null);
        $result = (new FullBaseUrlCoreHealthcheck())->check()->isPassed();
        $this->assertFalse($result);
    }
}
