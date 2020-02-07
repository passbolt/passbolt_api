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
 * @since         2.0.0
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
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Closure;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class ResourcesAddControllerTest extends AppIntegrationTestCase
{
    use FoldersRelationsModelTrait;
    use FoldersModelTrait;
    use FixtureProviderTrait;

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
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
        parent::setUp();
    }

    protected function _getGpgMessage()
    {
        return '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----';
    }

    protected function _getDummyPostData($data = [])
    {
        $defaultData = [
            'Resource' => [
                'name' => 'new resource name',
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => 'new resource description',
            ],
            'Secret' => [
                [
                    'data' => $this->_getGpgMessage(),
                ],
            ],
        ];
        $data = array_merge_recursive($defaultData, $data);

        return $data;
    }

    /**
     * @dataProvider provideResourcesAddSuccess
     * @param Closure $fixture
     * @param array $resourceData
     * @param string|null $expectedFolderParentId
     */
    public function testResourcesAddSuccessCreateFolderRelation(Closure $fixture, array $resourceData, string $expectedFolderParentId = null)
    {
        list($userId) = $this->executeFixture($fixture);

        $this->authenticateAs('ada');
        $this->postJson("/resources.json?api-version=2", $resourceData);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the relation
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId, $expectedFolderParentId);
    }

    public function provideResourcesAddSuccess()
    {
        $folderParentId = UuidFactory::uuid();

        $fixture = function () use ($folderParentId) {
            $userId = UuidFactory::uuid('user.id.ada');
            $this->addFolderFor(['id' => $folderParentId, 'name' => 'test'], [$userId => Permission::OWNER]);
            return [$userId];
        };

        return [
            'Without a parent folder' => [
                $fixture,
                $this->_getDummyPostData(),
                null
            ],
            'With a parent folder which exists' => [
                $fixture,
                $this->_getDummyPostData([
                    'Resource' => [
                        'folder_parent_id' => $folderParentId,
                    ]
                ]),
                $folderParentId
            ],
        ];
    }

    /**
     * @dataProvider provideResourcesAddError
     * @param Closure $fixture
     * @param int $responseCode
     * @param string $responseMessage
     * @param string $expectedErrorField
     * @param array $data
     */
    public function testResourcesAddErrorsDoesNotCreateFolderResourceIfFolderParentNotOk(
        Closure $fixture,
        int $responseCode,
        string $responseMessage,
        string $expectedErrorField,
        array $data = []
    )
    {
        $this->executeFixture($fixture);

        $this->authenticateAs('ada');
        $this->postJson("/resources.json?api-version=2", $data);
        $this->assertError($responseCode, $responseMessage);
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, $expectedErrorField);
        $this->assertNotNull($error);
        $this->assertResourceNotExist(['Resources.id' => $data['Resource']['name']]);
    }

    public function provideResourcesAddError()
    {
        $folderParentId = UuidFactory::uuid();

        $fixture = function () use ($folderParentId) {
            $this->addFolderFor(['id' => $folderParentId, 'name' => 'test'], [UuidFactory::uuid('user.id.betty') => Permission::OWNER]);
        };

        return [
            'Parent folder which does not exists' => [
                $fixture,
                'responseCode' => 400,
                'responseMessage' => 'Could not validate resource data',
                'errorField' => 'folder_parent_id.folder_exists',
                'data' => $this->_getDummyPostData([
                    'Resource' => [
                        'folder_parent_id' => UuidFactory::uuid(),
                    ]
                ]),
            ],
            'User does not have permission on parent folder' => [
                $fixture,
                'responseCode' => 403,
                'responseMessage' => 'Could not validate resource data',
                'errorField' => 'folder_parent_id.not_allowed',
                'data' => $this->_getDummyPostData([
                    'Resource' => [
                        'folder_parent_id' => $folderParentId,
                    ]
                ]),
            ],
        ];
    }
}
