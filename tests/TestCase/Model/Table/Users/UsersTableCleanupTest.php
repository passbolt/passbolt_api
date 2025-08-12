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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

/**
 * @covers \App\Model\Table\UsersTable
 */
class UsersTableCleanupTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->Users);
        parent::tearDown();
    }

    public function testUsersTableCleanupInactiveUsersWithDuplicatedUsername(): void
    {
        $duplicatedData = ['username' => 'duplicated@passbolt.com'];
        UserFactory::make(2)->patchData($duplicatedData)->inactive()->persist();
        UserFactory::make()->patchData($duplicatedData)->inactive()->deleted()->persist();
        UserFactory::make()->patchData($duplicatedData)->active()->deleted()->persist();
        UserFactory::make()->patchData($duplicatedData)->active()->persist();

        $duplicatedUsers = $this->Users->listDuplicateUsernames()->toArray();
        $duplicatedUserIds = array_keys($duplicatedUsers);
        $this->assertEquals(3, count($duplicatedUserIds));

        $toDeletedIds = $this->Users->find()
            ->select('id')
            ->where(['id IN' => $duplicatedUserIds, 'active' => false])
            ->all()
            ->extract('id')
            ->toArray();

        $this->assertEquals(2, count($toDeletedIds));
        $this->assertEquals(2, $this->Users->cleanupInactiveUsersWithDuplicatedUsername(true));
        $this->assertEquals(2, $this->Users->cleanupInactiveUsersWithDuplicatedUsername());
        $this->assertEquals(0, $this->Users->cleanupInactiveUsersWithDuplicatedUsername());

        $remains = $this->Users->find()
            ->select('id')
            ->where(['username' => 'duplicated@passbolt.com', 'active' => true, 'deleted' => false])
            ->count();

        $this->assertEquals(1, $remains);
    }

    public function testUsersTableCleanupInactiveUsersWithDuplicatedUsername_DoNotTouchActive(): void
    {
        $duplicatedData = ['username' => 'duplicated@passbolt.com'];
        UserFactory::make(2)->patchData($duplicatedData)->active()->persist();
        $this->assertEquals(0, $this->Users->cleanupInactiveUsersWithDuplicatedUsername());

        $remains = $this->Users->find()
            ->select('id')
            ->where(['username' => 'duplicated@passbolt.com', 'active' => true, 'deleted' => false])
            ->count();

        $this->assertEquals(2, $remains);
    }

    public function testUsersTableCleanupInactiveUsersWithDuplicatedUsername_EmptyUsers(): void
    {
        $this->assertEquals(0, $this->Users->cleanupInactiveUsersWithDuplicatedUsername());
    }
}
