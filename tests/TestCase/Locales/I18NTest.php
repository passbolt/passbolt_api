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
 * @since         3.1.0
 */

namespace App\Test\TestCase\Locales;

use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;

class I18NTest extends TestCase
{
    public function dataForFrenchTranslation(): array
    {
        return [
            ['Open source password manager for teams', 'Le gestionnaire de mots de passe pour votre équipe'],
        ];
    }

    /**
     * @dataProvider dataForFrenchTranslation
     */
    public function testFrenchTranslation($en, $fr)
    {
        $this->markTestSkipped('Add this once the translate tool is O.K.');
        I18n::setLocale('fr_FR');
        $this->assertSame($fr, I18n::getTranslator('fr_FR')->translate($en));
    }
}
