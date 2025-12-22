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
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Model\Table\RolesTable::validationDefault
 * @covers \App\Model\Table\RolesTable::beforeMarshal
 */
class RolesTableValidationTest extends TestCase
{
    /**
     * @var \App\Model\Table\RolesTable
     */
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

    public static function dataProviderForValidNameSuccess(): array
    {
        return [
            ['sales'],
            ['Sales Team'],
            ['équipe'],
            ['Développeur'],
            ['管理者'],
            ['Администратор'],
            ['مدير'],
        ];
    }

    /**
     * @dataProvider dataProviderForValidNameSuccess
     */
    public function testRolesTableValidation_Name_Success(string $name): void
    {
        $role = $this->Roles->newEntity(['name' => $name], ['accessibleFields' => ['name' => true]]);
        $this->assertEmpty($role->getErrors());
    }

    public static function dataProviderForInvalidNameError(): array
    {
        return [
            'emojis' => ['😄😄😄'],
            'empty' => [''],
            'reserved_guest' => ['guest'],
            'reserved_user' => ['user'],
            'reserved_admin' => ['admin'],
            'reserved_root' => ['root'],
        ];
    }

    /**
     * @dataProvider dataProviderForInvalidNameError
     */
    public function testRolesTableValidation_Name_Error(string $name): void
    {
        $role = $this->Roles->newEntity(['name' => $name], ['accessibleFields' => ['name' => true]]);
        $this->assertNotEmpty($role->getErrors());
        $this->assertArrayHasKey('name', $role->getErrors());
    }

    public function testRolesTableValidation_Name_MaxLength(): void
    {
        $role = $this->Roles->newEntity(['name' => str_repeat('a', 51)], ['accessibleFields' => ['name' => true]]);
        $this->assertNotEmpty($role->getErrors());
        $this->assertArrayHasKey('name', $role->getErrors());
    }

    public function testRolesTableValidation_Name_TrimWhitespace(): void
    {
        $role = $this->Roles->newEntity(['name' => '  sales  '], ['accessibleFields' => ['name' => true]]);
        $this->assertEmpty($role->getErrors());
        $this->assertSame('sales', $role->get('name'));
    }

    public function testRolesTableValidation_Name_TrimWhitespace_OnlyWhitespace(): void
    {
        $role = $this->Roles->newEntity(['name' => '   '], ['accessibleFields' => ['name' => true]]);
        $this->assertNotEmpty($role->getErrors());
        $this->assertArrayHasKey('name', $role->getErrors());
    }
}
