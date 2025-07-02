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
use Cake\Log\Log;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @covers JsonTraceFormatter
 */
class JsonTraceFormatterTest extends TestCase
{
    use IntegrationTestTrait;

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

    public function testJsonTraceFormatter_On_Hacked_Url_Should_Not_Throw_500_Exception()
    {
        $backupConfig = $logErrorConfig = Log::getConfig('error');
        // Set the formatter to JsonTrace
        $logErrorConfig['formatter'] = JsonTraceFormatter::class;
        Log::drop('error');
        Log::setConfig('error', $logErrorConfig);
        // Request a non parsable URL in JSON
        $this->get("/%c0%");
        $this->assertResponseError();
        // The response should not throw a 500
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Route');
        $this->assertResponseContains('A route matching `/�%` could not be found');
        Log::drop('error');
        Log::setConfig('error', $backupConfig);
    }
}
