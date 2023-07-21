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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterCreateService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterCreateService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterCreateService
 */
class ResourcesAfterCreateServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use ResourcesModelTrait;

    public $fixtures = [
        GroupsUsersFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
        UsersFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
    ];

    /**
     * @var ResourcesAfterCreateService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAfterCreateService();
    }

    public function testResourcesAfterCreateServiceSuccess_CreateToRoot()
    {
        [$resource, $userAId] = $this->insertFixture_CreateToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->afterCreate($uac, $resource);

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    private function insertFixture_CreateToRoot()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResource();
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, PermissionsTable::USER_ARO, $userAId);

        return [$resource, $userAId];
    }

    public function testResourcesAfterCreateServiceSuccess_CreateIntoFolder()
    {
        [$folder, $resource, $userAId] = $this->insertFixture_CreateIntoFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['folder_parent_id'] = $folder->id;
        $this->service->afterCreate($uac, $resource, $data);

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folder->id);
    }

    public function insertFixture_CreateIntoFolder()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userAId => Permission::OWNER]);
        $resource = $this->addResource();
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, PermissionsTable::USER_ARO, $userAId);

        return [$folder, $resource, $userAId];
    }

    public function testResourcesAfterCreateServiceError_FolderParentNotExist()
    {
        [$resource, $userAId] = $this->insertFixture_CreateToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data['folder_parent_id'] = UuidFactory::uuid();

        $this->service->afterCreate($uac, $resource, $data);
        $this->assertEntityError($resource, 'folder_parent_id.folder_exists');
    }

    public function testResourcesAfterCreateServiceError_FolderParentNotAllowed()
    {
        [$folder, $resource, $userAId, $userBId] = $this->insertFixture_FolderParentNotAllowed();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data['folder_parent_id'] = $folder->id;

        $this->service->afterCreate($uac, $resource, $data);
        $this->assertEntityError($resource, 'folder_parent_id.has_folder_access');
    }

    public function insertFixture_FolderParentNotAllowed()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folder = $this->addFolderFor([], [$userBId => Permission::OWNER]);

        return [$folder, $resource, $userAId, $userBId];
    }
}
