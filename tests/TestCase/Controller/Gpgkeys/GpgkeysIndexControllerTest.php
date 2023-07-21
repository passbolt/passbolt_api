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
namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class GpgkeysIndexControllerTest extends AppIntegrationTestCase
{
    public function testGpgkeysIndexController_Success(): void
    {
        UserFactory::make(21)->user()->with('Gpgkeys')->persist();

        $this->logInAsUser();
        $this->getJson('/gpgkeys.json');
        $this->assertSuccess();
        $this->assertGreaterThan(20, count($this->_responseJsonBody));
    }

    public function testGpgKeysIndexController_SuccessModifiedAfter(): void
    {
        UserFactory::make(21)
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey()->modifiedYesterday())
            ->persist();
        $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');

        // Find a key at a given time and modify it
        $t = FrozenTime::today();
        $gpgkey = $Gpgkeys->find('all')->first();
        $gpgkey->modified = FrozenTime::now();
        $Gpgkeys->save($gpgkey);

        // Find the keys modified since then
        $this->logInAsUser();
        $this->getJson('/gpgkeys.json?filter[modified-after]=' . $t->toUnixString());
        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
    }

    public function testGpgKeysIndexController_SuccessIsDeleted(): void
    {
        UserFactory::make(21)->user()->with('Gpgkeys')->persist();
        $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');

        // Find a key and set it deleted
        $gpgkey = $Gpgkeys->find('all')->first();
        $gpgkey->deleted = true;
        $Gpgkeys->save($gpgkey);

        // Find the keys deleted then
        $this->logInAsUser();
        $this->getJson('/gpgkeys.json?filter[is-deleted]=1');
        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);

        $gpgkey->deleted = true;
        $Gpgkeys->save($gpgkey);

        // Find the keys non deleted then (there should be one less)
        $this->getJson('/gpgkeys.json?filter[is-deleted]=0');
        $this->assertSuccess();
        $this->assertCount(20, $this->_responseJsonBody);
    }

    public function testGpgkeysIndexController_Error_NotAllowed(): void
    {
        $this->getJson('/gpgkeys.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testGpgkeysIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/gpgkeys');
        $this->assertResponseCode(404);
    }
}
