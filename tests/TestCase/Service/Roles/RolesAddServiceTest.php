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
use App\Model\Table\RolesTable;
use App\Service\Roles\RolesAddService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * @covers \App\Service\Roles\RolesAddService
 */
class RolesAddServiceTest extends AppTestCase
{
    private ?RolesAddService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new RolesAddService();
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

    public function testRolesAddService_Success(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($admin);
        $result = $this->service->add($uac, ['name' => 'marketing']);
        $this->assertTrue(Validation::uuid($result->id));
        $this->assertSame('marketing', $result->name);
        $this->assertSame($uac->getId(), $result->created_by);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertStringContainsString(DateTime::today()->toDateString(), $result->created->toIso8601String());
        $this->assertStringContainsString(DateTime::today()->toDateString(), $result->modified->toIso8601String());
        $this->assertSame(4, RoleFactory::count());
    }

    public static function invalidRolesValuesProvider(): array
    {
        return [
            [
                [], // empty data
                'name._required', // error path
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
                ['name' => 'admin'], // name is reserved
                'name.reservedRole',
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
     * @dataProvider invalidRolesValuesProvider
     * @return void
     */
    public function testRolesAddService_Error_InvalidData(array $invalidData, string $rulePath): void
    {
        $uac = $this->mockAdminAccessControl();

        try {
            $this->service->add($uac, $invalidData);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, $rulePath));
        }
    }

    public function testRolesAddService_Error_MaximumRole(): void
    {
        // 3 default roles already present
        RoleFactory::make(RolesTable::MAXIMUM_NO_OF_ROLES_ALLOWED - 3)->persist();
        $uac = $this->mockAdminAccessControl();

        try {
            $this->service->add($uac, ['name' => 'board members']);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, 'name.maximumNumberOfRolesAllowed'));
        }
    }
}
