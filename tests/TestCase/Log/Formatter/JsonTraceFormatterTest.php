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
 * @since         4.6.0
 */
namespace App\Test\TestCase\Log\Formatter;

use App\Log\Formatter\JsonTraceFormatter;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Log\Formatter\JsonTraceFormatter
 */
class JsonTraceFormatterTest extends TestCase
{
    /**
     * @return void
     */
    public function testJsonTraceFormatter()
    {
        $formatter = new JsonTraceFormatter();
        $level = 'foo';
        $message = 'bar';
        $traceAsString = '#1 line 1'. PHP_EOL . '#12 line 12';
        $context = [
            'trace' => $traceAsString,
            'moreContext' => 'baz'
        ];

        $result = $formatter->format($level, $message, $context);
        $result = json_decode($result, true);
        $expectedResult = [
            'date' => date(DATE_ATOM),
            'level' => $level,
            'message' => $message,
            'trace' => [
                '#1 line 1',
                '#12 line 12'
            ],
        ];
        $this->assertSame($expectedResult, $result);
    }

    public function testJsonTraceFormatter_On_Real_Trace()
    {
        $formatter = new JsonTraceFormatter();

        try {
            throw new \Exception();
        } catch (\Throwable $e) {
            $trace = $e->getTraceAsString();
        }

        $level = 'foo';
        $message = 'bar';
        $context = [
            'trace' => $trace,
            'moreContext' => 'baz'
        ];

        $result = $formatter->format($level, $message, $context);
        $result = json_decode($result, true);

        $traceInResult = $result['trace'];
        $this->assertStringContainsString(
            get_class($this) . '->testJsonTraceFormatter_On_Real_Trace()',
            $traceInResult[0]
        );
    }
}
