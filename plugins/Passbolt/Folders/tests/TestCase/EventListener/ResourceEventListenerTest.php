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

namespace Passbolt\Folders\Test\TestCase\EventListener;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Alt0\SecretsFixture;
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\EmailQueueFixture;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\OrganizationSettingsFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;
use Passbolt\Log\Test\Fixture\Base\ActionLogsFixture;
use Passbolt\Log\Test\Fixture\Base\ActionsFixture;
use Passbolt\Log\Test\Fixture\Base\EntitiesHistoryFixture;
use Passbolt\Log\Test\Fixture\Base\PermissionsHistoryFixture;

/**
 * \Passbolt\Folders\EventListener\ResourcesEventListener Test Case
 * @covers \Passbolt\Folders\EventListener\ResourcesEventListener
 */
class ResourceEventListenerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use ResourcesModelTrait;
    use SecretsModelTrait;

    public $fixtures = [
        ActionsFixture::class,
        ActionLogsFixture::class,
        EntitiesHistoryFixture::class,
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
        PermissionsHistoryFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
        EmailQueueFixture::class,
        AvatarsFixture::class,
        FavoritesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        OrganizationSettingsFixture::class,
    ];

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    public function setUp()
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testResourcesEventListenerSuccess_AfterResourceAdded()
    {
        list($userId, $folder) = $this->insertFixture_AfterResourceAdded();
        $data = [
            'name' => 'R1',
            'folder_parent_id' => $folder->id,
            'secrets' => [$this->getDummySecretData(['resource_id' => null])],
        ];

        $this->authenticateAs('ada');
        $this->postJson("/resources.json?api-version=v2", $data);
        $this->assertSuccess();

        $resource = $this->_responseJsonBody;
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId, $folder->id);
    }

    public function insertFixture_AfterResourceAdded()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $folder = $this->addFolderFor(['folder_parent_id' => $folder->id], [$userId => Permission::OWNER]);

        return [$userId, $folder];
    }

    public function testResourcesEventListenerSuccess_AfterResourceSoftDeleted()
    {
        list($userId, $resource) = $this->insertFixture_AfterResourceSoftDeleted();
        $data = $resource->toArray();
        $data['folder_parent_id'] = null;

        $this->authenticateAs('ada');
        $this->deleteJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertSuccess();

        $this->assertFolderRelationNotExist($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId);
    }

    public function insertFixture_AfterResourceSoftDeleted()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userId => Permission::OWNER]);

        return [$userId, $resource];
    }

    public function testResourcesEventListenerSuccess_AfterAccessGranted()
    {
        list($folder, $resource, $userAId, $userBId) = $this->insertFixture_AfterAccessGranted();

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userBId, 'data' => Hash::get($this->getDummySecretData(), 'data')];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folder->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folder->id);
    }

    public function insertFixture_AfterAccessGranted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor([], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userAId => Permission::OWNER]);

        return [$folder, $resource, $userAId, $userBId];
    }

    public function testResourcesEventListenerSuccess_AfterAccessRevoked()
    {
        list($folder, $resource, $userAId, $userBId) = $this->insertFixture_AfterAccessRevoked();

        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($resource->id, $userBId)->first();
        $data['permissions'][] = ['id' => $permission->id, 'delete' => true];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folder->id);
    }

    public function insertFixture_AfterAccessRevoked()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor([], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folder, $resource, $userAId, $userBId];
    }
}
