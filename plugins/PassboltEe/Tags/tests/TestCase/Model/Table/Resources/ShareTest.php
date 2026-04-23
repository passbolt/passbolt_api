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
use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Service\Resources\ResourcesShareService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class ShareTest extends TagTestCase
{
    public $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown(): void
    {
        unset($this->Resources);
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
        [$ada, $edith] = UserFactory::make(2)->persist();
        [$resourceToDeletePermissionOn, $resourceNotDeleted] = ResourceFactory::make(2)
            ->withPermissionsFor([$ada])
            ->withSecretRevisions()
            ->persist();
        $permissionToDelete = $resourceToDeletePermissionOn->permissions[0];

        TagFactory::make()
            ->isPersonalFor($resourceToDeletePermissionOn, $ada)
            ->isPersonalFor($resourceNotDeleted, $ada)
            ->persist();

        TagFactory::make()->isPersonalFor($resourceToDeletePermissionOn, $ada)->persist();

        // Build the changes.
        $changes = [];
        $secrets = [];

        // Users permissions changes.
        // Delete the permission of the user.
        $changes[] = ['id' => $permissionToDelete->id, 'delete' => true];
        // Add an owner otherwise we can't remove ada permission
        $changes[] = ['aro' => 'User', 'aro_foreign_key' => $edith->id, 'type' => Permission::OWNER];
        $secrets[] = ['user_id' => $edith->id, 'data' => $this->getValidSecret()];

        // Share.
        $uac = new UserAccessControl(Role::USER, $ada->id);
        $resourceShareService = new ResourcesShareService(new ResourcesExpireResourcesFallbackServiceService());
        $resourceShareService->share($uac, $resourceToDeletePermissionOn->id, $changes, $secrets);

        $this->assertUserHasNotAccessResources($ada->id, [$resourceToDeletePermissionOn->id]);

        // Ensure the tag for the resource with permission lost is deleted
        // But the other tags for this resource are not touched.
        $resources = ResourcesTagFactory::find()->where(['user_id' => $ada->id])->all();
        $resourceIds = Hash::extract($resources->toArray(), '{n}.resource_id');
        $this->assertNotContains($resourceToDeletePermissionOn->id, $resourceIds);
        $this->assertcontains($resourceNotDeleted->id, $resourceIds);
    }
}
