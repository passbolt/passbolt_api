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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class SaveTest extends AppTestCase
{
    public $Resources;

    public $fixtures = ['app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions'];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
    }

    public function testSaveCreateSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSaveUpdateSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSaveValidationError()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSaveCheckRulesError()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
