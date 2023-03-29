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
 * @since         4.0.0
 */
namespace App\Test\TestCase;

use Cake\I18n\FrozenDate;
use PHPUnit\Framework\TestCase;

/**
 * ChangelogFileTest class
 */
class ChangelogFileTest extends TestCase
{
    public const SECTION_NAME_ALLOWED = [
        'Added',
        'Chores',
        'Fixed',
        'Improved',
        'Maintenance',
        'Security',
        'Security fixes',
    ];

    public function testChangelogFile_Feature_File(): void
    {
        $file = $this->getFile(FIXTURES . 'Changelog' . DS . 'valid');
        $pointer = 0;
        $this->assertEquals('# Change Log', $file[$pointer]);

        // Jump to line 5 which has the tag number
        $line5 = $file[4];
        $this->assertEquals('## ', substr($line5, 0, 3));
        $this->assertEquals('[', substr($line5, 3, 1));
        $lastVersionLine = $line5;
        preg_match('#(?<=\[)(.*?)(?=\])#', $lastVersionLine, $lastVersionInChangeLogs);
        $tag = $lastVersionInChangeLogs[0];
        $this->assertIsString($tag);
        // The tag should not start with "v"
        $this->assertNotSame('v', substr($tag, 0, 1));
        $closingBracketPosition = strpos($line5, ']');
        // Tag in square brackets and date shouold be separated by " - "
        $this->assertEquals(' - ', substr($line5, $closingBracketPosition + 1, 3));
        $date = substr($line5, $closingBracketPosition + 4, 10);
        // The date should be in Y-M-d format
        $parsedDate = FrozenDate::parseDate($date, 'Y-M-d')->toDateString();
        $this->assertEquals($parsedDate, $date);
        // End of the line
        $this->assertSame($closingBracketPosition + 14, strlen($line5));

        // Jump to line 6 where the changes start
        $pointer = 5;
        while (substr($file[$pointer], 0, 4) !== '## [') {
            $pointer = $this->assertChangeBlock($file, $pointer);
        }
    }

    public function testChangelogFile_ChangeLog_File(): void
    {
        $file = $this->getFile(ROOT . DS . 'CHANGELOG');
        if (!isset($file[4])) {
            $this->markTestSkipped('No CHANGELOG.md file could be found');
        }
        $pointer = 0;
        $this->assertEquals('# Change Log', $file[$pointer]);

        // Jump to line 5 which has the tag number
        $line5 = $file[4];
        $this->assertEquals('## ', substr($line5, 0, 3));
        $this->assertEquals('[', substr($line5, 3, 1));
        $lastVersionLine = $line5;
        preg_match('#(?<=\[)(.*?)(?=\])#', $lastVersionLine, $lastVersionInChangeLogs);
        $tag = $lastVersionInChangeLogs[0];
        $this->assertIsString($tag);
        // The tag should not start with "v"
        $this->assertNotSame('v', substr($tag, 0, 1));
        $closingBracketPosition = strpos($line5, ']');
        // Tag in square brackets and date shouold be separated by " - "
        $this->assertEquals(' - ', substr($line5, $closingBracketPosition + 1, 3));
        $date = substr($line5, $closingBracketPosition + 4, 10);
        // The date should be in Y-M-d format
        $parsedDate = FrozenDate::parseDate($date, 'Y-M-d')->toDateString();
        $this->assertEquals($parsedDate, $date);
        // End of the line
        $this->assertSame($closingBracketPosition + 14, strlen($line5));

        // Jump to line 6 where the changes start
        $pointer = 5;
        while (substr($file[$pointer], 0, 4) !== '## [') {
            $pointer = $this->assertChangeBlock($file, $pointer);
        }
    }

    protected function getFile(string $name)
    {
        return file("{$name}.md", FILE_IGNORE_NEW_LINES);
    }

    protected function assertChangeBlock(array $file, int $position): int
    {
        $line1 = $file[$position];
        $line1 = explode(' ', $line1);
        $this->assertSame(2, count($line1));
        $this->assertSame('###', $line1[0]);
        $this->assertTrue(
            in_array(
                $line1[1],
                self::SECTION_NAME_ALLOWED
            ),
            'The sections allowed are: ' . implode(', ', self::SECTION_NAME_ALLOWED)
        );
        $position++;
        while ($file[$position] !== '') {
            $line = $file[$position];
            $this->assertSame('- ', substr($line, 0, 2), 'Each line should start with a dash and space');
            $this->assertSame('PB-', substr($line, 2, 3), 'Each line should start with a ticket number');
            $position++;
        }
        $position++;

        return $position;
    }
}
