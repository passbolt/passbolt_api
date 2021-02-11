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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Table\UsersTable;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class FindViewTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
        RoleFactory::make()->guest()->persist();
    }

    public function tearDown(): void
    {
        unset($this->Users);
    }

    public function testFindVew()
    {
        $user = UserFactory::make()->user()->persist();

        $result = $this->Users->findView($user->id, $user->role->name)->first();

        $this->assertSame($user->id, $result->id);
    }
}
