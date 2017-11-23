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

class SoftDeleteTest extends AppTestCase
{
    public $Users;

    public $fixtures = ['app.users', 'app.profiles', 'app.gpgkeys', 'app.roles', 'app.groups', 'app.groups_users', 'app.resources', 'app.permissions'];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
    }

    public function testSoftDeleteSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSoftDeleteAssociatedResourcesSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSoftDeleteCheckRulesGroupOwnerError()
    {
        // Frances is the only manager of group accountting and should not be deleted
        $user = $this->Users->get(UuidFactory::uuid('user.id.frances'));
        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
    }

    public function testSoftDeleteCheckRulesResourceOwnerError()
    {
        // Betty is the sole owner of some shared resources and should not be deleted
        $user = $this->Users->get(UuidFactory::uuid('user.id.betty'));
        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
    }
}
