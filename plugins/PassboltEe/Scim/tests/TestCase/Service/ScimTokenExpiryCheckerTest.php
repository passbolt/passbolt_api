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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Test\TestCase\Service;

use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;

/**
 * @covers \Passbolt\Scim\Service\ScimTokenExpiryChecker
 */
class ScimTokenExpiryCheckerTest extends TestCase
{
    private ScimTokenExpiryChecker $checker;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->checker = new ScimTokenExpiryChecker();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unset($this->checker);
        parent::tearDown();
    }

    public static function expiredProvider(): array
    {
        return [
            'expired yesterday' => [-1, true],
            'expires today' => [0, false],
            'expires in 5 days' => [5, false],
            'expires in 20 days' => [20, false],
        ];
    }

    /**
     * @dataProvider expiredProvider
     * @param int $dayOffset The offset in days from today.
     * @param bool $expected The expected result.
     * @return void
     */
    public function testScimTokenExpiryChecker_IsExpired(int $dayOffset, bool $expected): void
    {
        $config = ['expired' => Date::now()->addDays($dayOffset)->toDateString()];
        $this->assertSame($expected, $this->checker->isExpired($config));
    }

    public static function nearExpiryProvider(): array
    {
        return [
            'expired yesterday is not near' => [-1, false],
            'expires today is near' => [0, true],
            'expires in 5 days is near' => [5, true],
            'expires on the exact day of the window is near' => [14, true],
            'expires after the window is not near' => [15, false],
        ];
    }

    /**
     * @dataProvider nearExpiryProvider
     * @param int $dayOffset The offset in days from today.
     * @param bool $expected The expected result.
     * @return void
     */
    public function testScimTokenExpiryChecker_IsNearExpiry(int $dayOffset, bool $expected): void
    {
        Configure::write(ScimTokenExpiryChecker::CONFIG_WARNING_WINDOW, '14 days');
        $config = ['expired' => Date::now()->addDays($dayOffset)->toDateString()];
        $this->assertSame($expected, $this->checker->isNearExpiry($config));
    }

    public function testScimTokenExpiryChecker_IsExpired_MissingExpiredIsNotExpired(): void
    {
        $this->assertFalse($this->checker->isExpired([]));
    }

    public function testScimTokenExpiryChecker_IsNearExpiry_MissingExpiredIsNotNear(): void
    {
        $this->assertFalse($this->checker->isNearExpiry([]));
    }

    public function testScimTokenExpiryChecker_IsNearExpiry_InvalidValueFallbackOnDefault(): void
    {
        Configure::write(ScimTokenExpiryChecker::CONFIG_WARNING_WINDOW, null);
        $config = ['expired' => Date::now()->addDays(10)->toDateString()];
        $this->assertTrue($this->checker->isNearExpiry($config));
    }

    public function testScimTokenExpiryChecker_IsEnforced_Default(): void
    {
        Configure::delete(ScimTokenExpiryChecker::CONFIG_ENFORCE);
        $this->assertFalse($this->checker->isEnforced());
    }

    public function testScimTokenExpiryChecker_IsEnforced_ConfigValue(): void
    {
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, true);
        $this->assertTrue($this->checker->isEnforced());
    }
}
