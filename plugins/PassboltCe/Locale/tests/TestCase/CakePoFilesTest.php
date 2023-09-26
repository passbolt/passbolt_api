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
 * @since         4.3.0
 */
namespace Passbolt\Locale\Test\TestCase;

use PHPUnit\Framework\TestCase;

/**
 * Assert that cake .po files are present for all supported languages
 */
class CakePoFilesTest extends TestCase
{
    public function testCakePoFilesTest(): void
    {
        $path = RESOURCES . 'locales' . DS;
        $locales = array_diff(scandir($path), ['.', '..',]);
        foreach ($locales as $locale) {
            $this->assertFileExists(
                $path . $locale . DS . 'cake.po',
                'You may paste it from https://github.com/cakephp/localized/tree/4.x/resources/locales'
            );
        }
    }
}
