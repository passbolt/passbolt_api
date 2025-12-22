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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Model\Table\Roles;

use App\Model\Table\RolesTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class RolesTableSoftDeleteTest extends AppTestCase
{
    public RolesTable $Roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->Roles = TableRegistry::getTableLocator()->get('Roles');
    }

    public function tearDown(): void
    {
        unset($this->Roles);
        parent::tearDown();
    }

    public function testRolesTableSoftDelete_SoftDelete_Role_With_Associated_Users(): void
    {
        /** @var User $user */
        $user = UserFactory::make()->withRole('foo')->persist();
        $role = $user->role;

        $this->Roles->patchEntity($role, ['deleted' => FrozenTime::now()]);
        $result = $this->Roles->save($role);
        $this->assertFalse($result);
        $this->assertSame('The role cannot be deleted as it is associated with another user.', $role->getErrors()['id']['hasNoActiveUserAssociatedRule']);
    }

    public function testRolesTableSoftDelete_SoftDelete_Role_With_Deleted_Associated_Users(): void
    {
        /** @var User $user */
        $user = UserFactory::make()->withRole('foo')->deleted()->persist();
        $role = $user->role;

        $this->Roles->patchEntity($role, ['deleted' => FrozenTime::now()]);
        $result = $this->Roles->save($role);
        $this->assertNotNull($result->deleted);
    }
}
