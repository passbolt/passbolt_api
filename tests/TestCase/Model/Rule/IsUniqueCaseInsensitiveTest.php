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

namespace App\Test\TestCase\Model\Rule;

use App\Model\Rule\IsUniqueCaseInsensitive;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class IsUniqueCaseInsensitiveTest extends TestCase
{
    use TruncateDirtyTables;

    private IsUniqueCaseInsensitive $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new IsUniqueCaseInsensitive();
    }

    public function tearDown(): void
    {
        unset($this->rule);
        parent::tearDown();
    }

    public function testIsUniqueCaseInsensitive_Success_UniqueValue()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'betty@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertTrue($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_DuplicateExactCase()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_DuplicateDifferentCase()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'ADA@PASSBOLT.COM'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_DuplicateMixedCase()
    {
        UserFactory::make(['username' => 'Ada@Passbolt.Com'])->persist();

        $entity = UserFactory::make(['username' => 'aDA@pASSBOLT.cOM'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_MissingErrorField()
    {
        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'table' => 'Users',
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_MissingTable()
    {
        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_MissingAllOptions()
    {
        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, []);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Success_CheckDirtyFieldNotDirty()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();
        $entity->setDirty('username', false);

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
            'checkDirty' => true,
        ]);

        $this->assertTrue($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_CheckDirtyFieldIsDirty()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();
        $entity->setDirty('username', true);

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
            'checkDirty' => true,
        ]);

        $this->assertFalse($result);
    }

    public function testIsUniqueCaseInsensitive_Success_DuplicateButExistingIsDeleted()
    {
        UserFactory::make(['username' => 'ada@passbolt.com'])->deleted()->persist();

        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertTrue($result);
    }

    public function testIsUniqueCaseInsensitive_Success_DuplicateDifferentCaseButExistingIsDeleted()
    {
        UserFactory::make(['username' => 'ADA@PASSBOLT.COM'])->deleted()->persist();

        $entity = UserFactory::make(['username' => 'ada@passbolt.com'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertTrue($result);
    }

    public function testIsUniqueCaseInsensitive_Fail_DuplicateWithOneDeletedAndOneActive()
    {
        // One deleted user with same username
        UserFactory::make(['username' => 'ada@passbolt.com'])->deleted()->persist();
        // One active user with same username
        UserFactory::make(['username' => 'ada@passbolt.com'])->persist();

        $entity = UserFactory::make(['username' => 'ADA@PASSBOLT.COM'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'username',
            'table' => 'Users',
        ]);

        $this->assertFalse($result);
    }

    /**
     * Test with Roles table which has a datetime deleted column (not boolean)
     */
    public function testIsUniqueCaseInsensitive_Success_RoleWithDatetimeDeletedColumn()
    {
        RoleFactory::make(['name' => 'custom-role'])->deleted()->persist();

        $entity = RoleFactory::make(['name' => 'custom-role'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'name',
            'table' => 'Roles',
        ]);

        $this->assertTrue($result);
    }

    /**
     * Test with Roles table - duplicate should fail when existing role is not deleted
     */
    public function testIsUniqueCaseInsensitive_Fail_RoleWithDatetimeDeletedColumnNotDeleted()
    {
        RoleFactory::make(['name' => 'custom-role'])->persist();

        $entity = RoleFactory::make(['name' => 'CUSTOM-ROLE'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'name',
            'table' => 'Roles',
        ]);

        $this->assertFalse($result);
    }

    /**
     * Test with Roles table - case insensitive match with deleted record should pass
     */
    public function testIsUniqueCaseInsensitive_Success_RoleDifferentCaseButExistingIsDeleted()
    {
        RoleFactory::make(['name' => 'CUSTOM-ROLE'])->deleted()->persist();

        $entity = RoleFactory::make(['name' => 'custom-role'])->getEntity();

        $result = ($this->rule)($entity, [
            'errorField' => 'name',
            'table' => 'Roles',
        ]);

        $this->assertTrue($result);
    }
}
