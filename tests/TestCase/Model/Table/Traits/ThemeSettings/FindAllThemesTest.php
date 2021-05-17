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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Traits\ThemeSettings;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\AccountSettings\Model\Table\Traits\ThemeSettingsTrait;

class FindAllThemesTest extends TestCase
{
    /**
     * @see ThemeSettingsTrait::findAllThemes()
     */
    public function testFindAllThemes()
    {
        $table = $this->getObjectForTrait(ThemeSettingsTrait::class);

        $expected = [[
            'id' => '9a5ecc88-f4df-5cc2-b152-6ca310127a67',
            'name' => 'default',
            'preview' => Configure::read('App.fullBaseUrl') . '/img/themes/default.png',
        ],
        [
            'id' => '2e6d06eb-e417-5573-80ed-27b2182dc55b',
            'name' => 'midgar',
            'preview' => Configure::read('App.fullBaseUrl') . '/img/themes/midgar.png',
        ]];

        $response = $table->findAllThemes();
        $this->assertSame($expected, $response);
    }
}
