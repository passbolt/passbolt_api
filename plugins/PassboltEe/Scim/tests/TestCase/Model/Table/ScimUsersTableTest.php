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
 * @since         5.11.0
 */

namespace Passbolt\Scim\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Scim\Model\Table\ScimUsersTable;

/**
 * @covers \Passbolt\Scim\Model\Table\ScimUsersTable
 */
class ScimUsersTableTest extends AppTestCase
{
    use LocatorAwareTrait;

    private ScimUsersTable $ScimUsers;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \Passbolt\Scim\Model\Table\ScimUsersTable $table */
        $table = $this->fetchTable('Passbolt/Scim.ScimUsers');
        $this->ScimUsers = $table;
    }

    public function tearDown(): void
    {
        unset($this->ScimUsers);
        parent::tearDown();
    }

    public function testFindByEmailForScim_FindsActiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $result = $this->ScimUsers
            ->findByEmailForScim($user->username)
            ->first();

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->id);
    }

    public function testFindByEmailForScim_ExcludesDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->deleted()->persist();

        $result = $this->ScimUsers
            ->findByEmailForScim($user->username)
            ->first();

        $this->assertNull($result);
    }

    public function testFindByEmailForScim_ReturnsNullWhenNotFound(): void
    {
        $result = $this->ScimUsers
            ->findByEmailForScim('nonexistent@example.com')
            ->first();

        $this->assertNull($result);
    }

    public function testFindByEmailForScim_DoesNotContainForUpdateByDefault(): void
    {
        UserFactory::make()->user()->persist();

        $query = $this->ScimUsers->findByEmailForScim('test@example.com');
        $sql = $query->sql();

        $this->assertTextNotContains('FOR UPDATE', $sql);
    }

    public function testFindByEmailForScim_ContainsForUpdateWhenRequested(): void
    {
        UserFactory::make()->user()->persist();

        $query = $this->ScimUsers->findByEmailForScim('test@example.com', forUpdate: true);
        $sql = $query->sql();

        $this->assertTextContains('FOR UPDATE', $sql);
    }

    public function testFindForScim_FindsActiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $result = $this->ScimUsers
            ->findForScim([$this->ScimUsers->aliasField('id') => $user->id])
            ->first();

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->id);
    }

    public function testFindForScim_ExcludesDeletedByDefault(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->deleted()->persist();

        $result = $this->ScimUsers
            ->findForScim([$this->ScimUsers->aliasField('id') => $user->id])
            ->first();

        $this->assertNull($result);
    }

    public function testFindForScim_IncludesDeletedWhenRequested(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->deleted()->persist();

        $result = $this->ScimUsers
            ->findForScim(
                [$this->ScimUsers->aliasField('id') => $user->id],
                findDeleted: true
            )
            ->first();

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->id);
    }
}
