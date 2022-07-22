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
 * @since         3.7.0
 */

namespace App\Test\TestCase\Model\Validation\GroupsUsersChange;

use App\Model\Validation\GroupsUsersChange\GroupsUsersChangeValidator;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class GroupsUsersChangeValidatorTest extends AppTestCase
{
    /**
     * @var GroupsUsersChangeValidator
     */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new GroupsUsersChangeValidator();
    }

    /* Add change success */

    public function testGroupsUsersChangeValidatorSuccess_validGroupUserAddChange(): void
    {
        $change = [
            'user_id' => UuidFactory::uuid(),
            'is_admin' => false,
        ];

        $errors = $this->validator->validate($change);
        $this->assertEmpty($errors);
    }

    /* Add change error */

    public function testGroupsUsersChangeValidatorError_invalidGroupUserAddChange(): void
    {
        $change = [
            'user_id' => 'not-a-valid-uuid',
            'is_admin' => 42,
        ];

        $errors = $this->validator->validate($change);

        $this->assertNotEmpty($errors);
        $this->assertCount(2, $errors);
        $this->assertNotNull(Hash::get($errors, 'user_id.uuid'));
        $this->assertNotNull(Hash::get($errors, 'is_admin.boolean'));
    }

    /* Update change success */

    public function testGroupsUsersChangeValidatorSuccess_validGroupUserUpdateChange(): void
    {
        $change = [
            'id' => UuidFactory::uuid(),
            'is_admin' => false,
        ];

        $errors = $this->validator->validate($change);
        $this->assertEmpty($errors);
    }

    /* Update change error */

    public function testGroupsUsersChangeValidatorError_invalidGroupUserUpdateChange(): void
    {
        $change = [
            'id' => 'not-a-valid-uuid',
            'is_admin' => 42,
        ];

        $errors = $this->validator->validate($change);
        $this->assertNotEmpty($errors);
        $this->assertCount(2, $errors);
        $this->assertNotNull(Hash::get($errors, 'id.uuid'));
        $this->assertNotNull(Hash::get($errors, 'is_admin.boolean'));
    }

    /* Delete change success */

    public function testGroupsUsersChangeValidatorSuccess_validGroupUserDeleteChange(): void
    {
        $change = [
            'id' => UuidFactory::uuid(),
            'delete' => true,
        ];

        $errors = $this->validator->validate($change);
        $this->assertEmpty($errors);
    }

    /* Delete change error */

    public function testGroupsUsersChangeValidatorError_invalidGroupUserDeleteChange(): void
    {
        $change = [
            'id' => 'not-a-valid-uuid',
            'delete' => 42,
        ];

        $errors = $this->validator->validate($change);
        $this->assertNotEmpty($errors);
        $this->assertCount(2, $errors);
        $this->assertNotNull(Hash::get($errors, 'id.uuid'));
        $this->assertNotNull(Hash::get($errors, 'delete.boolean'));
    }

    /**
     * @dataProvider isAddChangeDataProvider
     */
    public function testGroupsUsersChangeValidator_isAddChange(array $change, bool $expected): void
    {
        $this->assertEquals(GroupsUsersChangeValidator::isAddChange($change), $expected);
    }

    public function isAddChangeDataProvider()
    {
        return [
            [
                'change' => ['user_id' => UuidFactory::uuid()],
                'expected' => true,
            ],
            [
                'change' => ['user_id' => UuidFactory::uuid(), 'is_admin' => true],
                'expected' => true,
            ],
            [
                'change' => ['user_id' => UuidFactory::uuid(), 'is_admin' => true],
                'expected' => true,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'is_admin' => true],
                'expected' => false,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'user_id' => UuidFactory::uuid(), 'is_admin' => true, 'delete' => false],
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider isUpdateChangeDataProvider
     */
    public function testGroupsUsersChangeValidator_isUpdateChange(array $change, bool $expected): void
    {
        $this->assertEquals(GroupsUsersChangeValidator::isUpdateChange($change), $expected);
    }

    public function isUpdateChangeDataProvider()
    {
        return [
            [
                'change' => ['id' => UuidFactory::uuid(), 'is_admin' => true],
                'expected' => true,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'is_admin' => false],
                'expected' => true,
            ],
            [
                'change' => ['user_id' => UuidFactory::uuid()],
                'expected' => false,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'delete' => true],
                'expected' => false,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'user_id' => UuidFactory::uuid(), 'is_admin' => true, 'delete' => false],
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider isDeleteChangeDataProvider
     */
    public function testGroupsUsersChangeValidator_isDeleteChange(array $change, bool $expected): void
    {
        $this->assertEquals(GroupsUsersChangeValidator::isDeleteChange($change), $expected);
    }

    public function isDeleteChangeDataProvider()
    {
        return [
            [
                'change' => ['id' => UuidFactory::uuid(), 'delete' => true],
                'expected' => true,
            ],
            [
                'change' => ['user_id' => UuidFactory::uuid()],
                'expected' => false,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'is_admin' => true],
                'expected' => false,
            ],
            [
                'change' => ['id' => UuidFactory::uuid(), 'user_id' => UuidFactory::uuid(), 'is_admin' => true, 'delete' => false],
                'expected' => false,
            ],
        ];
    }
}
