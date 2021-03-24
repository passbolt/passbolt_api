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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Passbolt\Locale\Utility\LocaleUtility;

class LocaleUtilityTest extends TestCase
{
    public function dataForTestLocaleUtilityLocaleIsValid(): array
    {
        return [
            ['en-US', true],
            ['en_US', true],
            ['xx-YY', false],
            ['', false],
            [null, false],
        ];
    }

    /**
     * @param string|null $locale
     * @param bool $expected
     * @dataProvider dataForTestLocaleUtilityLocaleIsValid
     */
    public function testLocaleUtilityLocaleIsValid(?string $locale, bool $expected): void
    {
        $this->assertSame(
            $expected,
            LocaleUtility::localeIsValid($locale)
        );
    }
}
