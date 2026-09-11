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
 * @since         5.14.0
 */

namespace App\Test\TestCase\Model\Rule\Role;

use App\Model\Entity\Role;
use App\Model\Rule\Role\IsReservedRoleNameUnchangedRule;
use App\Model\Table\RolesTable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Model\Rule\Role\IsReservedRoleNameUnchangedRule
 */
class IsReservedRoleNameUnchangedRuleTest extends TestCase
{
    private IsReservedRoleNameUnchangedRule $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new IsReservedRoleNameUnchangedRule();
    }

    public function testIsReservedRoleNameUnchangedRule_PassesWhenNameNotDirty(): void
    {
        $role = $this->makeRoleEntity(Role::ADMIN);

        $this->assertTrue(($this->sut)($role, []));
    }

    public function testIsReservedRoleNameUnchangedRule_PassesWhenOriginalNameIsCustom(): void
    {
        $role = $this->makeRoleEntity('sales');
        $role->set('name', 'growth', ['guard' => false]);

        $this->assertTrue(($this->sut)($role, []));
    }

    public function testIsReservedRoleNameUnchangedRule_FailsWhenRenamingAdminRole(): void
    {
        $role = $this->makeRoleEntity(Role::ADMIN);
        $role->set('name', 'foobar', ['guard' => false]);

        $this->assertFalse(($this->sut)($role, []));
    }

    /**
     * @dataProvider reservedRoleNamesProvider
     */
    public function testIsReservedRoleNameUnchangedRule_FailsForEachReservedName(string $reservedName): void
    {
        $role = $this->makeRoleEntity($reservedName);
        $role->set('name', 'renamed', ['guard' => false]);

        $this->assertFalse(($this->sut)($role, []));
    }

    public static function reservedRoleNamesProvider(): array
    {
        return array_map(fn(string $name) => [$name], RolesTable::RESERVED_ROLE_NAMES);
    }

    private function makeRoleEntity(string $name): Role
    {
        $role = new Role();
        $role->set('name', $name, ['guard' => false]);
        $role->clean();

        return $role;
    }
}
