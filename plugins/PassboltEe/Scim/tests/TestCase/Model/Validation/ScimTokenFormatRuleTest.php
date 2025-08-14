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
 * @since         5.5.0
 */

namespace Model\Validation;

use Cake\TestSuite\TestCase;
use Passbolt\Scim\Model\Validation\ScimTokenFormatRule;

/**
 * ScimTokenFormatRuleTest class
 */
class ScimTokenFormatRuleTest extends TestCase
{
    /**
     * @var \Passbolt\Scim\Model\Validation\ScimTokenFormatRule|\App\Model\Validation\EmailValidationRule|null
     */
    private ?ScimTokenFormatRule $rule = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new ScimTokenFormatRule();
    }

    /**
     * @return array[]
     */
    public static function ruleProvider(): array
    {
        return [
            [true, 'pb_WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            [true, '2a42c965559a7d0fbf1354e38a42f71f5436ea820450953e57b0aa81d794d5be'],
            [false, 'WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            [false, '2a42c965559a7d0fbf1354e38a42f71f5436ea820450953e57b0aa81d'],
            [false, 'random-string'],
        ];
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testTokenValidationRule(bool $expected, $value): void
    {
        $this->assertSame($expected, $this->rule->rule($value, null));
    }
}
