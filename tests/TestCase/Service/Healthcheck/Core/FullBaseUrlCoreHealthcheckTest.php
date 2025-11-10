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

    public static function validFullBaseUrlCoreHealthcheckStringValuesProvider(): array
    {
        return [
            [
                'https://cloud.passbolt.com/workspace', //url
                true, //expected result
            ],
            [
                'https://passbolt.com',
                true,
            ],
            [
                'https://passbolt.cloud',
                true,
            ],
            [
                'https://passbolt',
                true,
            ],
            [
                'https://localhost',
                true,
            ],
            [
                'https://localhost:8443',
                true,
            ],
            [
                'http://cloud.passbolt.com/workspace',
                true,
            ],
            [
                'http://passbolt.com',
                true,
            ],
            [
                'http://passbolt.cloud',
                true,
            ],
            [
                'http://passbolt',
                true,
            ],
            [
                'http://127.0.0.1',
                true,
            ],
            [
                'http://localhost',
                true,
            ],
            [
                'http://localhost:8080',
                true,
            ],
            [
                '🔥',
                true,
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
