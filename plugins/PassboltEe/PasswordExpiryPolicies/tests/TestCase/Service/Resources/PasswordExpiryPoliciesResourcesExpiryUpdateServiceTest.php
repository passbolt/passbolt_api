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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Service\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesResourcesExpiryUpdateService;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesValidationService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

class PasswordExpiryPoliciesResourcesExpiryUpdateServiceTest extends AppTestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    public PasswordExpiryPoliciesResourcesExpiryUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordExpiryPoliciesResourcesExpiryUpdateService(
            new PasswordExpiryPoliciesValidationService(
                new PasswordExpiryPoliciesGetSettingsService()
            )
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function validationFail(): array
    {
        $uuid = UuidFactory::uuid();
        $today = FrozenTime::tomorrow()->toDateTimeString();

        return [
            [['id' => null], 'An array of arrays is expected.'],
            [[['id' => null,]], 'The identifier should be a valid UUID.'],
            [[['id' => null, 'expired' => '2000-01-01']], 'The identifier should be a valid UUID.'],
            [[['id' => 'foo', 'expired' => '2000-01-01']], 'The identifier should be a valid UUID.'],
            [[
                ['id' => $uuid, 'expired' => $today],
                ['id' => $uuid, 'expired' => $today],
            ], "The identifier should be unique: $uuid."],
        ];
    }

    public function validationSuccess(): array
    {
        $uuid1 = UuidFactory::uuid();
        $uuid2 = UuidFactory::uuid();
        $pastDate = FrozenTime::yesterday()->toDateTimeString();
        $today = FrozenTime::tomorrow()->toDateTimeString();
        $atomStringFormat = FrozenTime::tomorrow()->toAtomString();

        return [
            [[['id' => $uuid1, 'expired' => null]]],
            [[['id' => $uuid1, 'expired' => $today]]],
            [[['id' => $uuid1, 'expired' => $atomStringFormat]]],
            [[['id' => $uuid1, 'expired' => $pastDate]]],
            [[
                ['id' => $uuid1, 'expired' => $today],
                ['id' => $uuid2, 'expired' => $today],
            ]],
        ];
    }

    /**
     * @dataProvider validationFail
     */
    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Validation_Fail(array $payload, $message)
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $uac = $this->mockUserAccessControl();
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage($message);
        $this->service->updateMany($uac, $payload);
    }

    /**
     * @dataProvider validationSuccess
     */
    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Validation_Success(array $payload)
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $uac = $this->mockUserAccessControl();
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You are not allowed to update these resources.');
        $this->service->updateMany($uac, $payload);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Empty_Payload_Should_Succeed()
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $uac = $this->mockUserAccessControl();
        $result = $this->service->updateMany($uac, []);
        $this->assertNull($result);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Update_Success()
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()
            ->withGroupsUsersFor([$user])
            ->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$user])
            ->expired(FrozenTime::today()->subDays(3))
            ->persist();
        $resource3 = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::UPDATE)
            ->expired(FrozenTime::today()->subDays(3))
            ->persist();
        $newExpiryDate1 = FrozenTime::today()->addDays(3);
        $newExpiryDate2 = null;
        $newExpiryDate3 = FrozenTime::today()->addDays(3);
        $payload = [
            ['id' => $resource1->get('id'), 'expired' => $newExpiryDate1->toAtomString()],
            ['id' => $resource2->get('id'), 'expired' => $newExpiryDate2],
            ['id' => $resource3->get('id'), 'expired' => $newExpiryDate3->toDateTimeString()],
        ];
        $uac = $this->makeUac($user);
        $this->service->updateMany($uac, $payload);

        $resource1 = ResourceFactory::get($resource1->get('id'));
        $resource2 = ResourceFactory::get($resource2->get('id'));
        $resource3 = ResourceFactory::get($resource3->get('id'));

        $this->assertEquals($newExpiryDate1, $resource1->get('expired'));
        $this->assertEquals($uac->getId(), $resource1->get('modified_by'));
        $this->assertNull($resource2->get('expired'));
        $this->assertEquals($uac->getId(), $resource2->get('modified_by'));
        $this->assertEquals($newExpiryDate3, $resource3->get('expired'));
        $this->assertEquals($uac->getId(), $resource3->get('modified_by'));
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Some_Resources_Are_Deleted()
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->expired(FrozenTime::today()->subDays(3))
            ->persist();
        $newExpiryDate = FrozenTime::today()->addDays(3);
        $uuidOfResourceNotInDB = UuidFactory::uuid();
        $payload = [
            ['id' => $resource->get('id'), 'expired' => $newExpiryDate->toDateTimeString()],
            ['id' => $uuidOfResourceNotInDB, 'expired' => $newExpiryDate->toDateTimeString()],
        ];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You are not allowed to update this resource: ' . $uuidOfResourceNotInDB);
        $this->service->updateMany($this->makeUac($user), $payload);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_Some_Resources_Max_Permission_Is_Read()
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()
            ->withGroupsUsersFor([$user])
            ->persist();
        $resourceId1 = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->expired(FrozenTime::today()->subDays(3))
            ->persist()->get('id');
        $resourceId2 = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::READ)
            ->expired(FrozenTime::today()->subDays(3))
            ->persist()->get('id');
        $newExpiryDate = FrozenTime::today()->addDays(3);
        $payload = [
            ['id' => $resourceId1, 'expired' => $newExpiryDate->toDateTimeString()],
            ['id' => $resourceId2, 'expired' => $newExpiryDate->toDateTimeString()],
        ];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You are not allowed to update this resource: ' . $resourceId2);
        $this->service->updateMany($this->makeUac($user), $payload);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateService_The_User_Doesnt_Have_Permission_At_All_To_Some_Resources()
    {
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = UserFactory::make()->persist();
        $resourceId1 = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->expired(FrozenTime::today()->subDays(3))
            ->persist()->get('id');
        $resourceId2 = ResourceFactory::make()->persist()->get('id');
        $newExpiryDate = FrozenTime::today()->addDays(3);
        $payload = [
            ['id' => $resourceId1, 'expired' => $newExpiryDate->toDateTimeString()],
            ['id' => $resourceId2, 'expired' => $newExpiryDate->toDateTimeString()],
        ];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You are not allowed to update this resource: ' . $resourceId2);
        $this->service->updateMany($this->makeUac($user), $payload);
    }
}
