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

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class BuildFromArmoredKey extends AppTestCase
{
    public $Gpgkeys;

    public $fixtures = ['app.users', 'app.gpgkeys'];

    public function setUp()
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::get('Gpgkeys');
    }

    public function tearDown()
    {
        unset($this->Gpgkeys);
        parent::tearDown();
    }

    public function testbuildEntityFromArmoredKeySuccess()
    {
        $armoredKey = file_get_contents(ROOT . '/plugins/PassboltTestData/config/gpg/ada_public.key');
    }
}
