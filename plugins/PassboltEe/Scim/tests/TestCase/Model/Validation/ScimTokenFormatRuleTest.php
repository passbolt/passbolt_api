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

namespace Passbolt\Scim\Test\TestCase\Model\Validation;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\Scim\Model\Validation\ScimTokenFormatRule;

/**
 * @covers \Passbolt\Scim\Model\Validation\ScimTokenFormatRule
 */
class ScimTokenFormatRuleTest extends AppTestCase
{
    /**
     * @var \Passbolt\Scim\Model\Validation\ScimTokenFormatRule|null
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
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->rule);
        parent::tearDown();
    }

    /**
     * @return array[]
     */
    public static function ruleProviderDefault(): array
    {
        return [
            'valid raw token' => [true, 'pb_WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            'valid SHA-256 hex' => [true, '2a42c965559a7d0fbf1354e38a42f71f5436ea820450953e57b0aa81d794d5be'],
            'valid bcrypt $2y$' => [true, '$2y$12$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'valid bcrypt $2b$' => [true, '$2b$10$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'raw token too short' => [false, 'pb_WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc'],
            'missing pb_ prefix' => [false, 'WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            'SHA-256 hex too short' => [false, '2a42c965559a7d0fbf1354e38a42f71f5436ea820450953e57b0aa81d'],
            'bcrypt too short' => [false, '$2y$12$tooshort'],
            'wrong bcrypt prefix' => [false, '$2x$12$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'random string' => [false, 'random-string'],
            'null value' => [false, null],
            'integer value' => [false, 123],
        ];
    }

    /**
     * @dataProvider ruleProviderDefault
     * @param bool $expected Expected value.
     * @param mixed $value Value to test.
     * @return void
     */
    public function testScimTokenFormatRule(bool $expected, mixed $value): void
    {
        $this->assertSame($expected, $this->rule->rule($value, null));
    }

    /**
     * @return array[]
     */
    public static function ruleProviderLegacyHashDisabled(): array
    {
        return [
            'valid raw token' => [true, 'pb_WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            'valid bcrypt $2y$' => [true, '$2y$12$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'valid bcrypt $2b$' => [true, '$2b$10$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'legacy SHA-256 hex not allowed' => [false, '2a42c965559a7d0fbf1354e38a42f71f5436ea820450953e57b0aa81d794d5be'],
            'raw token too short' => [false, 'pb_WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc'],
            'missing pb_ prefix' => [false, 'WRIuQrV60RPApixBVd1BA6FEQ2u32AgkNRyhczzVwc2'],
            'bcrypt too short' => [false, '$2y$12$tooshort'],
            'wrong bcrypt prefix' => [false, '$2x$12$WApznUPhDubN0oeveSXHxOgvCKnBBTfnhPfJYkHeE3tlVQ7fd4MYO'],
            'random string' => [false, 'random-string'],
            'null value' => [false, null],
            'integer value' => [false, 123],
        ];
    }

    /**
     * @dataProvider ruleProviderLegacyHashDisabled
     * @param bool $expected Expected value.
     * * @param mixed $value Value to test.
     * @return void
     */
    public function testScimTokenFormatRule_LegacySha256_RejectedWhenDisabled(bool $expected, mixed $value): void
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.legacyHashAllowed', false);
        $this->assertSame($expected, $this->rule->rule($value, null));
    }
}
