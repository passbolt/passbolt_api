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
 * @since         3.8.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Error\Exception\NoAdminInDbException;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class FindFirstAdminTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->Users);
    }

    public function testUsersTable_findFirstAdminOrThrowNoAdminInDbException(): void
    {
        try {
            $this->Users->findFirstAdminOrThrowNoAdminInDbException();
        } catch (\Throwable $e) {
            $this->assertInstanceOf(NoAdminInDbException::class, $e);
        }

        $admin = UserFactory::make()->admin()->persist();
        $user = $this->Users->findFirstAdminOrThrowNoAdminInDbException();
        $this->assertSame($admin->get('id'), $user->get('id'));
    }
}
