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
 * @since         4.2.0
 */

namespace App\Test\TestCase\Database\Type;

use App\Middleware\UuidParserMiddleware;
use App\Model\Entity\User;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Http\TestRequestHandler;
use App\Utility\UuidFactory;
use Cake\Database\Driver\Mysql;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class LowerCaseUuidTypeTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function testLowerCaseUuidType_New_Entity()
    {
        (new UuidParserMiddleware())->process(
            new ServerRequest(),
            new TestRequestHandler()
        );
        $uuid = UuidFactory::uuid();
        $UUID = strtoupper($uuid);

        $data = [
            'id' => $UUID,
            'username' => 'john@passbolt.com',
            'role_id' => $UUID,
            'profile' => [
                'id' => $UUID,
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
        ];
        $user = $this->Users->newEntity($data, [
        'accessibleFields' => [
            '*' => true,
        ],
        'associated' => [
            'Profiles' => [
                'accessibleFields' => ['*' => true],
            ],
        ],
        ]);
        $this->assertEmpty($user->getErrors());

        $this->assertSame($uuid, $user->id);
        $this->assertSame($uuid, $user->role_id);
        $this->assertSame($uuid, $user->profile->id);
    }

    public function testLowerCaseUuidType_Save_Entity()
    {
        $uuid = UuidFactory::uuid();
        $UUID = strtoupper($uuid);
        $role = RoleFactory::make()->user()->persist();

        $data = [
            'id' => $UUID,
            'username' => 'john@passbolt.com',
            'role_id' => strtoupper($role->get('id')),
        ];

        // Skip marshalling when saving by using the entity object directly
        $user = new User($data);
        $savedUser = $this->Users->save($user);
        $this->assertEmpty($user->getErrors());
        $this->assertSame($UUID, $savedUser['id']);

        // Retrieve the entity in the DB, skip marshalling, do not convert result in entities
        $userInDB = UserFactory::find()->disableHydration()->firstOrFail();
        $this->assertSame($uuid, $userInDB['id']);
        $this->assertSame($role->get('id'), $userInDB['role_id']);
    }

    public function testLowerCaseUuidType_With_UUID_Upper_case_In_Db_will_not_be_mapped_lower_cased_On_Mysql()
    {
        $uuid = UuidFactory::uuid();
        $UUID = strtoupper($uuid);
        // Insert a user with upper-cased UUID directly in the DB
        UserFactory::make()->getTable()
            ->getConnection()
            ->insert('users', [
                'id' => $UUID,
                'username' => 'Foo',
                'role_id' => $UUID,
                'created' => FrozenDate::now()->format('Y-m-d'),
                'modified' => FrozenDate::now()->format('Y-m-d'),
            ]);

        $userQueriedWithLowerCase = UserFactory::get($uuid);
        $userQueriedWithUpperCase = UserFactory::get($UUID);

        if (UserFactory::make()->getTable()->getConnection()->getDriver() instanceof Mysql) {
            $this->assertSame($UUID, $userQueriedWithUpperCase['id']);
            $this->assertSame($UUID, $userQueriedWithLowerCase['id']);
        } else {
            $this->assertSame($uuid, $userQueriedWithUpperCase['id']);
            $this->assertSame($uuid, $userQueriedWithLowerCase['id']);
        }
    }
}
