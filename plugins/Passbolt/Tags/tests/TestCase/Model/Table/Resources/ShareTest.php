<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Tags\Test\TestCase\Model\Table\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Service\Resources\ResourcesShareService;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Lib\TagTestCase;

class ShareTest extends TagTestCase
{
    public $Permissions;
    public $Resources;
    public $ResourcesTags;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Favorites',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Alt0/Secrets',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
    }

    public function tearDown()
    {
        unset($this->Permissions);
        unset($this->Resources);
        unset($this->Tags);
        parent::tearDown();
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

    public function testTagsLostAccessAssociatedDataDeleted()
    {
        $resourceAId = UuidFactory::uuid('resource.id.apache');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userEId = UuidFactory::uuid('user.id.edith');

        // Build the changes.
        $changes = [];
        $secrets = [];

        // Users permissions changes.
        // Delete the permission of the user.
        $permission = $this->Permissions->find('all')
            ->where(['aco_foreign_key' => $resourceAId, 'aro_foreign_key' => $userAId])
            ->first();
        $changes[] = ['id' => $permission->id, 'delete' => true];
        // Add a owner otherwise we can't remove ada permission
        $changes[] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $secrets[] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];

        // Share.
        $uac = new UserAccessControl(Role::USER, $userAId);
        $resourceShareService = new ResourcesShareService();
        $resourceShareService->share($uac, $resourceAId, $changes, $secrets);

        $this->assertUserHasNotAccessResources($userAId, [$resourceAId]);

        // Ensure the apache favorite for Dame is deleted
        // But the other favorites for this resource are not touched.
        $resources = $this->ResourcesTags->find()
            ->where(['user_id' => $userAId])
            ->all();
        $resourcesId = Hash::extract($resources->toArray(), '{n}.resource_id');
        $this->assertNotContains($resourceAId, $resourcesId);
        $this->assertcontains(UuidFactory::uuid('resource.id.april'), $resourcesId);
        $this->assertcontains(UuidFactory::uuid('resource.id.chai'), $resourcesId);
    }
}
