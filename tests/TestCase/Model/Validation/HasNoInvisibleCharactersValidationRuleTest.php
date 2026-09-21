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
 * @since         5.15.0
 */

namespace App\Test\TestCase\Model\Validation;

use App\Model\Validation\HasNoInvisibleCharactersValidationRule;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Model\Validation\HasNoInvisibleCharactersValidationRule
 */
class HasNoInvisibleCharactersValidationRuleTest extends TestCase
{
    private HasNoInvisibleCharactersValidationRule $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new HasNoInvisibleCharactersValidationRule();
    }

    public function tearDown(): void
    {
        unset($this->rule);
        parent::tearDown();
    }

    public static function visibleStringsProvider(): array
    {
        return [
            'ascii' => ['sales'],
            'ascii_with_space' => ['Sales Team'],
            'french' => ['équipe'],
            'accented' => ['Développeur'],
            'japanese' => ['管理者'],
            'cyrillic' => ['Администратор'],
            'arabic' => ['مدير'],
            'digits_and_dash' => ['team-42'],
            'punctuation' => ["Bob's crew"],
        ];
    }

    /**
     * @dataProvider visibleStringsProvider
     */
    public function testHasNoInvisibleCharactersValidationRule_Rule_Success(string $value): void
    {
        $this->assertTrue($this->rule->rule($value, null));
    }

    public static function invisibleStringsProvider(): array
    {
        return [
            'only_invisible_character' => ["\u{200B}"],
            'zero_width_space' => ["sales\u{200B}team"],
            'zero_width_non_joiner' => ["sales\u{200C}team"],
            'zero_width_joiner' => ["sales\u{200D}team"],
            'left_to_right_mark' => ["sales\u{200E}team"],
            'right_to_left_mark' => ["sales\u{200F}team"],
            'word_joiner' => ["sales\u{2060}team"],
            'byte_order_mark' => ["\u{FEFF}sales"],
            'soft_hyphen' => ["sales\u{00AD}team"],
            'line_separator' => ["sales\u{2028}team"],
            'paragraph_separator' => ["sales\u{2029}team"],
            'no_break_space' => ["sales\u{00A0}team"],
            'narrow_no_break_space' => ["sales\u{202F}team"],
            'medium_math_space' => ["sales\u{205F}team"],
            'ideographic_space' => ["sales\u{3000}team"],
            'ogham_space' => ["sales\u{1680}team"],
            'en_quad' => ["sales\u{2000}team"],
            'em_space' => ["sales\u{2003}team"],
            'hair_space' => ["sales\u{200A}team"],
            'nbsp_only' => ["\u{00A0}\u{00A0}"],
            'null' => ["sales\u{0000}team"],
            'tab' => ["sales\u{0009}team"],
            'line_feed' => ["sales\u{000A}team"],
            'carriage_return' => ["sales\u{000D}team"],
            'escape' => ["sales\u{001B}team"],
            'delete' => ["sales\u{007F}team"],
            'next_line_c1' => ["sales\u{0085}team"],
            'private_use_area_start' => ["sales\u{E000}team"],
            'private_use_area_end' => ["sales\u{F8FF}team"],
            'variation_selector_1' => ["sales\u{FE00}team"],
            'variation_selector_15_text' => ["sales\u{FE0E}team"],
            'variation_selector_16_emoji' => ["sales\u{FE0F}team"],
            'multiple_zero_width' => ["there\u{200B}s\u{200B}an\u{200B}invalid\u{200B}char"],
        ];
    }

    /**
     * @dataProvider invisibleStringsProvider
     */
    public function testHasNoInvisibleCharactersValidationRule_Rule_RejectsInvisible(string $value): void
    {
        $this->assertFalse($this->rule->rule($value, null));
    }

    public function testHasNoInvisibleCharactersValidationRule_Rule_RejectsNonString(): void
    {
        $this->assertFalse($this->rule->rule(null, null));
        $this->assertFalse($this->rule->rule(42, null));
        $this->assertFalse($this->rule->rule(['sales'], null));
    }

    public function testHasNoInvisibleCharactersValidationRule_DefaultErrorMessage(): void
    {
        $this->assertSame(
            'The string should not contain invisible characters.',
            $this->rule->defaultErrorMessage('any', null),
        );
    }
}
