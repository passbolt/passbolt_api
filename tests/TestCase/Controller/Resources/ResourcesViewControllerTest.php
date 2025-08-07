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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesViewControllerTest extends AppIntegrationTestCase
{
    use FavoritesModelTrait;
    use GroupsModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(FoldersPlugin::class);
    }

    public function testResourcesViewController(): void
    {
        $user = $this->logInAsUser();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist()->id;
        $this->getJson("/resources/$resourceId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody);
    }

    public function testResourcesViewController_Success_InUseContain(): void
    {
        $user = $this->logInAsUser();
        [$groupA, $groupB] = GroupFactory::make(2)->persist();
        $resourceId = ResourceFactory::make()
            ->withPermissionsFor([$groupA, $groupB], Permission::READ)
            ->withPermissionsFor([$user])
            ->with('Modifier')
            ->with('Favorites', FavoriteFactory::make()->setUser($user))
            ->withSecretsFor([$user])
            ->withCreator(UserFactory::make())
            ->persist()->id;
        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[secret]=1';
        $urlParameter .= '&contain[permission]=1&contain[permissions]=1';
        $urlParameter .= '&contain[permissions.user.profile]=1&contain[permissions.group]=1';
        $this->getJson("/resources/$resourceId.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        // Contain creator.
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->creator);

        // Contain modifier.
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->modifier);

        // Contain permission.
        $this->assertObjectHasAttribute('permission', $this->_responseJsonBody);
        $this->assertPermissionAttributes($this->_responseJsonBody->permission);

        // Contain permissions.
        $this->assertObjectHasAttribute('permissions', $this->_responseJsonBody);
        $this->assertPermissionAttributes($this->_responseJsonBody->permissions[0]);

        // Contain permissions.user.
        $this->assertObjectHasAttribute('permissions', $this->_responseJsonBody);
        foreach ($this->_responseJsonBody->permissions as $permission) {
            if ($permission->aro === 'User') {
                $this->assertUserAttributes($permission->user);
                $this->assertProfileAttributes($permission->user->profile);
            } else {
                $this->assertGroupAttributes($permission->group);
            }
        }

        // Contain secret.
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody->secrets[0]);

        $this->getJson("/resources/$resourceId.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        // Contain favorite.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody);

        // A resource marked as favorite contains the favorite data.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->favorite);
    }

    public function testResourcesViewController_Error_NotAuthenticated(): void
    {
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resources/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testResourcesViewController_Error_NotValidId(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testResourcesViewController_Error_NotFound(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesViewController_Error_ResourceWithDeletedResourceType(): void
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->with('ResourceTypes', ResourceTypeFactory::make()->passwordDescriptionTotp()->deleted())
            ->persist();

        $this->getJson("/resources/{$resource->get('id')}.json");

        $this->assertNotFoundError('The resource does not exist');
    }

    public function testResourcesViewController_Error_SoftDeletedResource(): void
    {
        $this->logInAsUser();
        $resourceId = ResourceFactory::make()->deleted()->persist()->id;
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesViewController_Error_ResourceAccessDenied(): void
    {
        $resourceId = ResourceFactory::make()->persist()->id;

        // Check that the resource exists.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->logInAsUser();
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testResourcesViewController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->get("/resources/$resourceId");
        $this->assertResponseCode(404);
    }
}
