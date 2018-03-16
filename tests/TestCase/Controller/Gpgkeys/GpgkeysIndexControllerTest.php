<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class GpgkeysIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/roles', 'app.Base/gpgkeys'];

    public function testGpgkeysIndexNotAllowedError()
    {
        $this->getJson('/gpgkeys.json?api-version=v1');
        $this->assertAuthenticationError();
    }

    public function testGpgkeysIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys.json?api-version=v1');
        $this->assertSuccess();
        $this->assertGreaterThan(20, count($this->_responseJsonBody));
    }

    public function testGpgKeysIndexModifiedAfterSuccess()
    {
        $Gpgkeys = TableRegistry::get('Gpgkeys');

        // Find a key at a given time and modify it
        $t = Time::parse('now');
        sleep(1);
        $gpgkey = $Gpgkeys->find('all')->first();
        $gpgkey->modified = Time::parse('now');
        $Gpgkeys->save($gpgkey);

        // Find the keys modified since then
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys.json?api-version=v1&filter[modified-after]=' . $t->toUnixString());
        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
    }
}
