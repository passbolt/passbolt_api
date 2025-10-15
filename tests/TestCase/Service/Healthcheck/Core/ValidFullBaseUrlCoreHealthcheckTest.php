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
 * @since         4.11.0
 */
namespace App\Test\TestCase\Service\Healthcheck\Core;

use App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use stdClass;

/**
 * @covers \App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck
 */
class ValidFullBaseUrlCoreHealthcheckTest extends TestCase
{
    /**
     * @dataProvider validFullBaseUrlCoreHealthcheckUrlsProvider
     */
    public function testValidFullBaseUrlCoreHealthcheck_IsPassed(string $url, bool $expectedResult): void
    {
        $result = (new ValidFullBaseUrlCoreHealthcheck($url))->check()->isPassed();
        $this->assertSame($expectedResult, $result);
    }

    public static function validFullBaseUrlCoreHealthcheckUrlsProvider(): array
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
                'url' => 'http://localhost',
                'expected result' => true,
            ],
            [
                'url' => 'http://localhost:8080',
                'expected result' => true,
            ],
            [
                'url' => 'htt://cloud.passbolt.com/workspace',
                'expected result' => false,
            ],
            [
                'url' => 'ftp://localhost:8080',
                'expected result' => false,
            ],
            [
                'url' => 'gopher://localhost:8080',
                'expected result' => false,
            ],
            [
                'url' => '🔥',
                'expected result' => false,
            ],
        ];
    }

    public function testValidFullBaseUrlCoreHealthcheck_InvalidFullBaseUrl(): void
    {
        Configure::write('App.fullBaseUrl', false);
        $healthcheck = new ValidFullBaseUrlCoreHealthcheck();
        $result = $healthcheck->check()->isPassed();
        $this->assertFalse($result);
        $this->assertStringContainsString(
            'IMPORTANT: Using an empty App.fullBaseUrl can lead to host header injection attack: https://owasp.org/www-project-web-security-testing-guide/v42/4-Web_Application_Security_Testing/07-Input_Validation_Testing/17-Testing_for_Host_Header_Injection',
            $healthcheck->getHelpMessage()[2]
        );
    }

    public function testValidFullBaseUrlCoreHealthcheck_InvalidTypeFullBaseUrl(): void
    {
        Configure::write('App.fullBaseUrl', new stdClass());
        $healthcheck = new ValidFullBaseUrlCoreHealthcheck();
        $result = $healthcheck->check()->isPassed();
        $this->assertFalse($result);
        $this->assertStringContainsString(
            'App.fullBaseUrl does not validate. A valid URL/IP is accepted, but found "object".',
            $healthcheck->getFailureMessage()
        );
    }
}
