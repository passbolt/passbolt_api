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
    public array $errors = [];

    public const SECTION_NAME_ALLOWED = [
        'Added',
        'Changed',
        'Chores',
        'Fixed',
        'Improved',
        'Maintenance',
        'Removed',
        'Security',
        'Security fixes',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->errors = [];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->errors);
    }

    public function testChangelogFile_ChangeLog_File(): void
    {
        $file = $this->getFile(ROOT . DS . 'CHANGELOG');
        if (!isset($file[4])) {
            $this->markTestSkipped('No valid CHANGELOG.md file could be found');
        }

        $this->collectChangelogErrors($file);
        $this->assertEmpty($this->errors);
    }

    public function testChangelogFile_Valid_Fixture_File(): void
    {
        $file = $this->getFile(FIXTURES . 'Changelog' . DS . 'valid');
        $this->collectChangelogErrors($file);
        $this->assertEmpty($this->errors);
    }

    public function testChangelogFile_InvalidFile(): void
    {
        $file = $this->getFile(FIXTURES . 'Changelog' . DS . 'invalid');
        $this->collectChangelogErrors($file);
        $this->assertSame(7, count($this->errors));
    }

    protected function collectChangelogErrors(array $file)
    {
        $pointer = 0;
        $this->appendErrorIf($file[$pointer] !== '# Change Log', 'The first line should be "# Change Log"', $pointer);

        // Jump to line 5 which has the tag number
        $pointer = 4;
        $line5 = $file[$pointer];
        $this->appendErrorIf(substr($line5, 0, 3) !== '## ', 'Tag line should start with ## ', $pointer);
        $this->appendErrorIf(substr($line5, 3, 1) !== '[', 'Square bracket expected', $pointer);

        // Assert that tag is well defined
        $lastVersionLine = $line5;
        preg_match('#(?<=\[)(.*?)(?=\])#', $lastVersionLine, $lastVersionInChangeLogs);
        $tag = $lastVersionInChangeLogs[0];
        $this->appendErrorIf(!is_string($tag), 'Tag could not be parsed', $pointer);

        // The tag should not start with "v"
        $this->appendErrorIf(substr($tag, 0, 1) === 'v', 'Tag should not start with v', $pointer);

        // Tag in square brackets and date should be separated by " - "
        $closingBracketPosition = strpos($line5, ']');
        $this->appendErrorIf(
            substr($line5, $closingBracketPosition + 1, 3) !== ' - ',
            'Tag and date should be separated by a -',
            $pointer
        );

        // The date should be in Y-M-d format
        $date = substr($line5, $closingBracketPosition + 4, 10);
        $parsedDate = FrozenDate::parseDate($date, 'Y-M-d')->toDateString();
        $this->appendErrorIf($parsedDate !== $date, 'The date could not be parsed', $pointer);

        // Jump to line 6 where the changes block starts
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
        $line = $file[$position];
        $line = explode(' ', $line);
        $this->appendErrorIf(count($line) !== 2, 'The change section should start with ### ', $position);
        $this->appendErrorIf($line[0] !== '###', 'The change section should start with ### ', $position);
        $this->appendErrorIf(!in_array(
            $line[1],
            self::SECTION_NAME_ALLOWED
        ), 'The sections allowed are: ' . implode(', ', self::SECTION_NAME_ALLOWED), $position);

        $position++;
        while ($file[$position] !== '') {
            $line = $file[$position];
            $this->appendErrorIf(substr($line, 0, 2) !== '- ', 'Dash is missing', $position);
            $this->appendErrorIf(
                substr($line, 2, 3) !== 'PB-',
                'Each line should start with a ticket number',
                $position
            );
            $position++;
        }
        $position++;

        return $position;
    }

    protected function appendErrorIf(bool $condition, string $msg, int $pointer): void
    {
        $pointer++;
        if ($condition) {
            $this->errors[] = $msg . " :: line #{$pointer}";
        }
    }
}
