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
use App\Model\Entity\Resource;
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

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
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

    protected function getValidSecret()
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }


    protected function _getDummyPostdata($resource = null, $data = [])
    {
        // Build the default data
        $defaultData = [
            'name' => 'Resource name updated by test',
            'username' => 'username_updated@by.test',
            'uri' => 'https://uri.updated.by.test',
            'description' => 'Resource description updated',
        ];

        // If secrets provided update them all.
        if (isset($resource->secrets)) {
            foreach ($resource->secrets as $secret) {
                $defaultData['secrets'][] = [
                    'id' => $secret->id,
                    'user_id' => $secret->user_id,
                    'data' => $this->getValidSecret(),
                ];
            }
        }

        $data = array_merge($defaultData, $data);

        return $data;
    }

    /**
     * @dataProvider provideResourcesAddSuccess
     * @param Closure $fixture
     * @param array $updatedResourceData
     * @param string|null $expectedFolderParentId
     */
    public function testResourcesUpdateSuccessCreateFolderRelation(Closure $fixture, array $updatedResourceData, string $expectedFolderParentId = null)
    {
        /** @var Resource $resource */
        list($userId, $resource) = $this->executeFixture($fixture);

        $updatedResourceData = $this->_getDummyPostData($resource, $updatedResourceData);

        $this->authenticateAs('ada');
        $this->putJson("/resources/{$resource->id}.json?api-version=2", $updatedResourceData);
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
            $resource = $this->addResourceForUsers([], [$userId => Permission::OWNER]);
            return [$userId, $resource];
        };

        return [
            'Without a parent folder' => [
                $fixture,
                [],
                null
            ],
            'With a parent folder which exists' => [
                $fixture,
                [
                    'Resource' => [
                        'folder_parent_id' => $folderParentId,
                    ]
                ],
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
