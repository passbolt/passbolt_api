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
 * @since         4.10.0
 */

namespace Passbolt\ResourceTypes\Test\TestCase\Service;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;
use Passbolt\ResourceTypes\Service\ResourceTypesDeleteService;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;

/**
 * @covers \Passbolt\ResourceTypes\Service\ResourceTypesDeleteService
 */
class ResourceTypesDeleteServiceTest extends AppTestCaseV5
{
    use ResourceTypesModelTrait;

    /**
     * @return void
     */
    public function testResourceTypesDeleteService_Delete_Success_V4DeleteV4Type(): void
    {
        MetadataSettingsFactory::make()->v4()->persist();

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $sut->delete($uac, $resourceTypeId);
        $this->assertEquals(2, ResourceTypeFactory::count());
        $updatedResourceType = ResourceTypeFactory::get($resourceTypeId);
        $this->assertNotNull($updatedResourceType->deleted);
    }

    public function testResourceTypesDeleteService_Delete_V5DeleteV4Type(): void
    {
        MetadataSettingsFactory::make()->v5()->persist();

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->v5PasswordString()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $sut->delete($uac, $resourceTypeId);
        $this->assertEquals(2, ResourceTypeFactory::count());
        $updatedResourceType = ResourceTypeFactory::get($resourceTypeId);
        $this->assertNotNull($updatedResourceType->deleted);
    }

    public function testResourceTypesDeleteService_Delete_V6DeleteV5Type(): void
    {
        MetadataSettingsFactory::make()->v6()->persist();

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->v5PasswordString()->persist();
        ResourceTypeFactory::make()->v5Default()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $sut->delete($uac, $resourceTypeId);
        $this->assertEquals(2, ResourceTypeFactory::count());
        $updatedResourceType = ResourceTypeFactory::get($resourceTypeId);
        $this->assertNotNull($updatedResourceType->deleted);
    }

    public function testResourceTypesDeleteService_Delete_ErrorHighlanderV4(): void
    {
        MetadataSettingsFactory::make()->v4()->persist();

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class); // There can be only one!
        $sut->delete($uac, $resourceTypeId);
    }

    public function testResourceTypesDeleteService_Delete_ErrorHighlanderV5(): void
    {
        MetadataSettingsFactory::make()->v5()->persist();

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->v5PasswordString()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class); // There can be only one!
        $sut->delete($uac, $resourceTypeId);
    }

    public function testResourceTypesDeleteService_Delete_ErrorSomeResourcesExists(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;
        ResourceFactory::make()
            ->patchData(['resource_type_id' => $resourceTypeId])
            ->persist();

        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class);
        $sut->delete($uac, $resourceTypeId);
    }

    public function testResourceTypesDeleteService_Delete_ErrorAlreadyDeleted(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $sut->delete($uac, $resourceTypeId);
        $this->expectException(BadRequestException::class);
        $sut->delete($uac, $resourceTypeId);
    }

    public function testResourceTypesDeleteService_Delete_ErrorNotFound(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $sut = new ResourceTypesDeleteService();
        $this->expectException(NotFoundException::class);
        $sut->delete($uac, UuidFactory::uuid());
    }

    public function testResourceTypesDeleteService_Delete_ErrorNotUuid(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class);
        $sut->delete($uac, '🔥');
    }

    public function testResourceTypesDeleteService_UndoDelete_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->deleted()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $sut->undoDelete($uac, $resourceTypeId);

        $modified = ResourceTypeFactory::get($resourceTypeId);
        $this->assertNull($modified->deleted);
    }

    public function testResourceTypesDeleteService_UndoDelete_ErrorNotDeleted(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;

        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class);
        $sut->undoDelete($uac, $resourceTypeId);
    }

    public function testResourceTypesDeleteService_UndoDelete_ErrorNotFound(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $sut = new ResourceTypesDeleteService();
        $this->expectException(NotFoundException::class);
        $sut->undoDelete($uac, UuidFactory::uuid());
    }

    public function testResourceTypesDeleteService_UndoDelete_ErrorNotUuid(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $sut = new ResourceTypesDeleteService();
        $this->expectException(BadRequestException::class);
        $sut->undoDelete($uac, '🔥');
    }
}
