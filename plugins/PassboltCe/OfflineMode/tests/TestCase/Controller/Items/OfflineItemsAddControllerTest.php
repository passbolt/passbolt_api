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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Controller\Items;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\OfflineMode\Controller\Items\OfflineItemsAddController
 */
class OfflineItemsAddControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
        $this->enableFeaturePlugin(RbacsPlugin::class);
        OfflineModeSettingFactory::make()->persist();
    }

    public function testOfflineItemsAddController_Success(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertSuccess();

        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body['id']);
        $this->assertSame($user->get('id'), $body['user_id']);
        $this->assertSame(OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $body['foreign_model']);
        $this->assertSame($resourceId, $body['foreign_key']);
        $this->assertSame($user->get('id'), $body['created_by']);
        $this->assertNotEmpty($body['created']);

        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddController_Success_Idempotent(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertSuccess();
        $firstId = $this->getResponseBodyAsArray()['id'];

        // Reset the table locator between HTTP calls to prevent "Association alias `<Model>` is already set." errors.
        TableRegistry::getTableLocator()->clear();

        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertSuccess();
        $secondId = $this->getResponseBodyAsArray()['id'];

        $this->assertSame($firstId, $secondId);
        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddController_Success_AcceptsPutMethod(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->putJson("/offline/resource/$resourceId.json");
        $this->assertSuccess();

        $body = $this->getResponseBodyAsArray();
        $this->assertSame($resourceId, $body['foreign_key']);
        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddController_Error_BadRequest_InvalidUuid(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $this->logInAs($user);

        $this->postJson('/offline/resource/invalid-id.json');
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testOfflineItemsAddController_Error_NotFound_ResourceMissing(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $this->logInAs($user);

        $missingId = '00000000-0000-0000-0000-000000000000';
        $this->postJson("/offline/resource/$missingId.json");
        $this->assertNotFoundError('The resource does not exist.');
    }

    public function testOfflineItemsAddController_Error_NotFound_ResourceSoftDeleted(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->setDeleted()->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertNotFoundError('The resource does not exist.');
    }

    public function testOfflineItemsAddController_Error_NotFound_NoAccess(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->persist(); // no permission for $user
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertNotFoundError('The resource does not exist.');
    }

    public function testOfflineItemsAddController_Error_NotAuthenticated(): void
    {
        $resource = ResourceFactory::make()->persist();
        $resourceId = $resource->get('id');

        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testOfflineItemsAddController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertForbiddenError('Missing or incorrect CSRF cookie type.');
    }

    public function testOfflineItemsAddController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->post("/offline/resource/$resourceId");
        $this->assertResponseCode(404);
    }

    public function testOfflineItemsAddController_Error_OfflineModeDisabled(): void
    {
        $this->disableOfflineMode();
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $resourceId = $resource->get('id');
        $this->logInAs($user);
        $this->postJson("/offline/resource/$resourceId.json");

        $this->assertForbiddenError('Offline Mode is not enabled at the org level.');
    }

    public function testOfflineItemsAddController_Error_NotAuthenticated_WhenOfflineModeDisabled(): void
    {
        $this->disableOfflineMode();
        $resource = ResourceFactory::make()->persist();

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");

        $this->assertAuthenticationError();
    }

    public function testOfflineItemsAddController_Error_RbacDenied(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testOfflineItemsAddController_Error_ForbiddenIfItemIsV4(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");

        $this->assertForbiddenError('Offline mode is only available for v5 items.');
        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddController_Error_InvalidForeignModel(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $resourceId = $resource->get('id');
        $this->postJson("/offline/widget/$resourceId.json");

        $this->assertBadRequestError(
            'The offline item object type should be one of the following: Resource.',
        );
    }

    public function testOfflineItemsAddController_Error_MaxItemsReached(): void
    {
        $this->disableOfflineMode();
        OfflineModeSettingFactory::make()->setMaxItems(1)->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        OfflineItemFactory::make()->setUser($user)->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();

        $this->logInAs($user);
        $resourceId = $resource->get('id');
        $this->postJson("/offline/resource/$resourceId.json");

        $this->assertBadRequestError('Could not validate offline item data');
        $this->assertArrayHasKey('max_items', $this->getResponseBodyAsArray());
        $this->assertSame(1, OfflineItemFactory::count());
    }

    private function seedRbacAllow(string $roleId): void
    {
        $action = ActionFactory::make()
            ->name(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_ADD)
            ->persist();

        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $roleId)
            ->allow()
            ->persist();
    }

    private function disableOfflineMode(): void
    {
        $table = OfflineModeSettingFactory::make()->getTable();
        $table->deleteAll([]);
    }
}
