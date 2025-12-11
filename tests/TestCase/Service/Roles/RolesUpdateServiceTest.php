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

namespace App\Test\TestCase\Service\Roles;

use App\Error\Exception\CustomValidationException;
use App\Service\Roles\RolesUpdateService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * @covers \App\Service\Roles\RolesUpdateService
 */
class RolesUpdateServiceTest extends AppTestCase
{
    private ?RolesUpdateService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new RolesUpdateService();
        // populate default roles
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->admin()->persist();
        RoleFactory::make()->user()->persist();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testRolesUpdateService_Success(): void
    {
        $ada = UserFactory::make()->user()->persist();
        $admin = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($admin);

        $role = RoleFactory::make([
            'created_by' => $ada->id,
            'modified_by' => $ada->id,
            'created' => DateTime::today()->subDays(7),
            'modified' => DateTime::today()->subDays(7),
        ])->persist();
        $result = $this->service->update($uac, $role->id, ['name' => 'marketing']);

        $this->assertTrue(Validation::uuid($result->id));
        // Assert data updated in the database
        $this->assertSame(4, RoleFactory::count());
        $role = RoleFactory::get($role->id);
        $this->assertSame('marketing', $role->name);
        $this->assertSame($ada->id, $role->created_by);
        $this->assertSame($uac->getId(), $role->modified_by);
        $this->assertStringContainsString(DateTime::today()->toDateString(), $role->modified->toIso8601String());
        $this->assertNull($role->deleted);
        $this->assertNull($role->deleted_by);
    }

    public static function invalidRolesUpdateValuesProvider(): array
    {
        return [
            [
                [], // empty data
                'name._empty', // error path
            ],
            [
                ['name' => ''], // empty name
                'name._empty',
            ],
            [
                ['name' => []], // name not a string
                'name.utf8',
            ],
            [
                ['name' => 'admin'], // name is not unique
                'name.unique',
            ],
            [
                ['name' => 'a loooooooooooooooooooooooong naaaaaaaaaaaaaaaaaame'], // name too long
                'name.maxLength',
            ],
            [
                ['name' => 'root'], // reserved role
                'name.reservedRole',
            ],
        ];
    }

    /**
     * @dataProvider invalidRolesUpdateValuesProvider
     * @return void
     */
    public function testRolesUpdateService_Error_InvalidData(array $invalidData, string $rulePath): void
    {
        $uac = $this->mockAdminAccessControl();
        $role = RoleFactory::make()->persist();

        try {
            $this->service->update($uac, $role->id, $invalidData);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, $rulePath));
        }
    }

    /**
     * @return void
     */
    public function testRolesUpdateService_Error_DuplicateName(): void
    {
        $uac = $this->mockAdminAccessControl();
        RoleFactory::make(['sales'])->persist();
        $role = RoleFactory::make(['Sles'])->persist();

        try {
            $this->service->update($uac, $role->id, ['name' => 'Sales']);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, 'name.unique'));
        }
    }
}
