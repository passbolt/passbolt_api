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
 * @since         4.6.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Table\UsersTable;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class UsersTableDisableUserTest extends TestCase
{
    use TruncateDirtyTables;

    public UsersTable $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public static function invalidDates(): array
    {
        return [
            [date('Y-m-d\TH:i:sP')],
            [true],
        ];
    }

    /**
     * @dataProvider invalidDates
     */
    public function testUsersTableDisableUser_InvalidDate($date)
    {
        RoleFactory::make()->user()->persist();

        $user = UserFactory::make()
            ->persist();
        $admin = UserFactory::make()->admin()->nonPersistedUAC();
        $data = [
            'id' => $user->id,
            'disabled' => $date,
        ];

        $user = $this->Users->editEntity(
            $user,
            $data,
            $admin
        );

        $this->Users->save($user);

        $this->assertSame(
            ['dateTime' => 'The disabled date should be a valid date.'],
            $user->getError('disabled')
        );
    }
}
