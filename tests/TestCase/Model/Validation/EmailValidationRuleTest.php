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
 * @since         3.11.0
 */

namespace App\Test\TestCase\Model\Validation;

use App\Model\Validation\EmailValidationRule;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;

/**
 * @group emailValidation
 */
class EmailValidationRuleTest extends TestCase
{
    /**
     * @var EmailValidationRule
     */
    private $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new EmailValidationRule();
    }

    public function regexProvider(): array
    {
        return [
            [true, 'ab@test.test'],
            [true, 'ab@test'],
            [true, 'ab'],
            [false, 'b@test.test'],
            [false, 'b@test'],
        ];
    }

    /**
     * @dataProvider regexProvider
     */
    public function testEmailValidationRule_With_Regex(bool $expected, $value): void
    {
        // Rule is an "a" followed by a b.
        $regex = '/a(b)/';
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, $regex);
        $this->assertSame($expected, $this->rule->rule($value, null));
    }

    public function testEmailValidationRule_With_Non_String_Regex_Should_Throw_Internal_Exception(): void
    {
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, 1);
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('The regular expression should be a valid string.');
        $this->rule->rule('foo', null);
    }

    public function testEmailValidationRule_With_Non_String_Regex_And_Check_Skipped_Should_Not_Throw_Internal_Exception(): void
    {
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, 1);
        $this->assertTrue($this->rule->withoutRegexCheck()->rule('test@foo.foo', null));
    }

    public function testEmailValidationRule_With_Invalid_Regex(): void
    {
        $this->markTestSkipped('This will throw a warning. So skipped for the moment');
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, 'hello');
        $this->assertFalse($this->rule->rule('foo', null));
    }

    public function testEmailValidationRule_With_Mx(): void
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $this->assertTrue($this->rule->rule('test@passbolt.com', null));
        $this->assertFalse($this->rule->rule('test@test.qwertzuiop', null));
        $this->assertTrue($this->rule->withoutMxCheck()->rule('test@test.qwertzuiop', null));
    }
}
