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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
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
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use FoldersRelationsModelTrait;
    use FoldersModelTrait;
    use FixtureProviderTrait;
    use ResourcesModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
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
     * @var ResourcesTable
     */
    protected $Resources;

    /**
     * @var FoldersRelationsTable
     */
    protected $FoldersRelations;

    /**
     * @var PermissionsTable
     */
    protected $Permissions;

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testResourcesUpdateSuccessCase1_MoveToRoot()
    {
        list($userId, $resource) = $this->insertFixtureForCase1_MoveToRoot();
        $data = $resource->toArray();
        $data['folder_parent_id'] = null;

        $this->authenticateAs('ada');
        $this->putJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertSuccess();

        $resource = $this->_responseJsonBody;
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId, null);
    }

    public function insertFixtureForCase1_MoveToRoot()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userId => Permission::OWNER]);

        return [$userId, $resource];
    }

    public function testResourcesUpdateSuccessCase2_MoveIntoPersonnalFolder()
    {
        list($userId, $resource, $folder) = $this->insertFixtureForCase2_MoveIntoPersonnalFolder();
        $data = $resource->toArray();
        $data['folder_parent_id'] = $folder->id;

        $this->authenticateAs('ada');
        $this->putJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertSuccess();

        $resource = $this->_responseJsonBody;
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId, $folder->id);
    }

    public function insertFixtureForCase2_MoveIntoPersonnalFolder()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $resource = $this->addResourceFor([], [$userId => Permission::OWNER]);

        return [$userId, $resource, $folder];
    }

    public function testResourcesUpdateErrorCase3_FolderParentNotExist()
    {
        list($resource) = $this->insertFixtureForCase3_FolderParentNotExist();
        $data = $resource->toArray();
        $data['folder_parent_id'] = UuidFactory::uuid();

        $this->authenticateAs('ada');
        $this->putJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertError(400, 'Could not validate resource data');
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, 'folder_parent_id.folder_exists');
        $this->assertNotNull($error);
    }

    public function insertFixtureForCase3_FolderParentNotExist()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResourceFor([], [$userId => Permission::OWNER]);

        return [$resource];
    }

    public function testResourcesUpdateErrorCase4_FolderParentNotAllowed()
    {
        list($resource, $folder) = $this->insertFixtureForCase4_FolderParentNotAllowed();
        $data = $resource->toArray();
        $data['folder_parent_id'] = $folder->id;

        $this->authenticateAs('ada');
        $this->putJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertError(400, 'Could not validate resource data');
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, 'folder_parent_id.has_folder_access');
        $this->assertNotNull($error);
    }

    public function insertFixtureForCase4_FolderParentNotAllowed()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor([], [$userBId => Permission::OWNER]);

        return [$resource, $folder];
    }
}
