<?php
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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterUpdateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * \Passbolt\Folders\Service\Resources\ResourcesAfterUpdateService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterUpdateService
 */
class ResourcesAfterUpdateServiceTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var ResourcesAfterUpdateService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = new ResourcesAfterUpdateService();
    }

    public function testResourcesAfterUpdateSuccess()
    {
        list($folderA, $resource, $userAId) = $this->insertFixture_ResourcesAfterUpdateSuccess();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data['folder_parent_id'] = $folderA->id;

        $this->service->afterUpdate($uac, $resource, $data);

        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
    }

    public function insertFixture_ResourcesAfterUpdateSuccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor([], [$userAId => Permission::OWNER]);
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $userAId];
    }

    public function testResourcesAfterUpdateError_FolderParentIdValidation()
    {
        list($resource, $userAId) = $this->insertFixture_ResourcesAfterUpdateError();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data['folder_parent_id'] = 42;

        try {
            $this->service->afterUpdate($uac, $resource, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate resource data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['uuid' => 'The folder parent id is not valid.']];
            $this->assertEquals($errors, $e->getErrors());
        }
    }

    public function insertFixture_ResourcesAfterUpdateError()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);

        return [$resource, $userAId];
    }

    public function testResourcesAfterUpdateError_MoveValidation()
    {
        list($resource, $userAId) = $this->insertFixture_MoveValidation();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data['folder_parent_id'] = UuidFactory::uuid();

        try {
            $this->service->afterUpdate($uac, $resource, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate resource data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['folder_exists' => 'The folder parent does not exist.']];
            $this->assertEquals($errors, $e->getErrors());
        }
    }

    public function insertFixture_MoveValidation()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);

        return [$resource, $userAId];
    }
}
