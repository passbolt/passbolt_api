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
 * @since         5.4.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Table\UsersTable;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\I18n\DateTime;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * @covers \App\Model\Table\UsersTable
 */
class UsersTableFindIndexTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    public UsersTable|Table $Users;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unset($this->Users);
        parent::tearDown();
    }

    /**
     * Last logged in should be returning calculated results value from action logs and not from the actual field value.
     *
     * @covers \App\Model\Traits\Users\UsersFindersTrait
     * @return void
     */
    public function testUsersTableFindIndex_ContainLastLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        UserFactory::make()->user()->inactive()->persist();
        UserFactory::make()->user()->disabled()->persist();
        UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make(['last_logged_in' => DateTime::now()->subDays(2)])
            ->admin()
            ->active()
            ->persist();
        // Set last logged in for user
        ActionLogFactory::make(['created' => DateTime::now()->subMinutes(1)])
            ->loginAction()
            ->userId($admin->id)
            ->persist();
        $actionLogLatest = ActionLogFactory::make(['created' => DateTime::now()])
            ->loginAction()
            ->userId($admin->id)
            ->persist();

        $options = ['contain' => ['last_logged_in' => 1]];
        $result = $this->Users->findIndex($active->role->name, $options);

        /** @var \App\Model\Entity\User $user */
        foreach ($result->all()->toArray() as $user) {
            if ($user->id === $admin->id) {
                $this->assertNotNull($user->last_logged_in);
                $this->assertSame($actionLogLatest->get('created')->toIso8601String(), $user->last_logged_in->toIso8601String());
                break;
            }
        }
    }

    /**
     * @covers \App\Model\Traits\Users\UsersFindersTrait
     * @return void
     */
    public function testUsersTableFindIndex_LastLoggedInWithoutContain(): void
    {
        RoleFactory::make()->guest()->persist();
        $ada = UserFactory::make()->admin()->active()->persist();
        UserFactory::make(['last_logged_in' => DateTime::now()])->user()->active()->persist();

        $result = $this->Users->findIndex($ada->role->name);

        /** @var \App\Model\Entity\User $user */
        foreach ($result->all()->toArray() as $user) {
            $this->assertNull($user->last_logged_in);
        }
    }
}
