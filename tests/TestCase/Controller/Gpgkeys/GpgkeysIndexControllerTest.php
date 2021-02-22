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

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class GpgkeysIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Gpgkeys',
    ];

    public function testGpgkeysIndexNotAllowedError()
    {
        $this->getJson('/gpgkeys.json');
        $this->assertAuthenticationError();
    }

    public function testGpgkeysIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys.json');
        $this->assertSuccess();
        $this->assertGreaterThan(20, count($this->_responseJsonBody));
    }

    public function testGpgKeysIndexModifiedAfterSuccess()
    {
        $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');

        // Find a key at a given time and modify it
        $t = Time::parse('now');
        sleep(1);
        $gpgkey = $Gpgkeys->find('all')->first();
        $gpgkey->modified = Time::parse('now');
        $Gpgkeys->save($gpgkey);

        // Find the keys modified since then
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys.json?filter[modified-after]=' . $t->toUnixString());
        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
    }

    public function testGpgKeysIndexIsDeletedSuccess()
    {
        $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');

        // Find a key and set it deleted
        $gpgkey = $Gpgkeys->find('all')->where(['user_id' => UuidFactory::uuid('user.id.ada')])->first();
        $gpgkey->deleted = true;
        $Gpgkeys->save($gpgkey);

        // Find the keys deleted then
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys.json?filter[is-deleted]=1');
        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);

        $gpgkey->deleted = true;
        $Gpgkeys->save($gpgkey);

        // Find the keys non deleted then (there should be none)
        $this->getJson('/gpgkeys.json?filter[is-deleted]=0');
        $this->assertSuccess();
        $this->assertCount(24, $this->_responseJsonBody);
    }
}
