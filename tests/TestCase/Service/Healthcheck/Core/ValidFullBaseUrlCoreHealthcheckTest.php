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
use Cake\TestSuite\TestCase;

class ValidFullBaseUrlCoreHealthcheckTest extends TestCase
{
    public function testValidFullBaseUrlCoreHealthcheckTest_Success(): void
    {
        $pass = [
            'https://cloud.passbolt.com/workspace',
            'https://passbolt.com',
            'https://passbolt.cloud',
            'https://passbolt',
            'https://localhost',
            'https://localhost:8443',
            'http://cloud.passbolt.com/workspace',
            'http://passbolt.com',
            'http://passbolt.cloud',
            'http://passbolt',
            'http://localhost',
            'http://localhost:8080',
        ];
        foreach ($pass as $url) {
            $test = (new ValidFullBaseUrlCoreHealthcheck($url))->check()->isPassed();
            if (!$test) {
                $this->fail('Test failed: ' . $url);
            } else {
                $this->assertTrue(true);
            }
        }
    }

    public function testValidFullBaseUrlCoreHealthcheckTest_Fail(): void
    {
        $pass = [
            'htt://cloud.passbolt.com/workspace',
            'ftp://localhost:8080',
            'gopher://localhost:8080',
            '🔥',
        ];
        foreach ($pass as $url) {
            $test = (new ValidFullBaseUrlCoreHealthcheck($url))->check()->isPassed();
            if ($test) {
                $this->fail('Test failed: ' . $url);
            } else {
                $this->assertTrue(true);
            }
        }
    }
}
