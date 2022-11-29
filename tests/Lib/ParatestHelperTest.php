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
 * @since         3.9.0
 */
namespace App\Test\Lib;

use Cake\TestSuite\TestCase;

class ParatestHelperTest extends TestCase
{
    /**
     * This test tests nothing. It is placed at the beginning of each test suite
     * used by the paratest tool (see bin/paratest).
     * We found out that each test suite starting with a test using static fixtures
     * would fail. This test is therefore added at the beginning of each suite.
     * Once the static fixtures are not used any more, this can be deleted.
     */
    public function testParatestHelper_AssertTrue(): void
    {
        $this->assertTrue(true);
    }
}
