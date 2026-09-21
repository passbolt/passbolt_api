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
namespace Passbolt\OfflineMode\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineResourceIndexListener
 */
class OfflineResourcesIndexControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
        $this->enableFeaturePlugin(RbacsPlugin::class);
        OfflineModeSettingFactory::make()->persist();
    }

    public function testOfflineResourcesIndexController_NoContain_NoOfflineKey(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $this->getJson('/resources.json');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body);
        $this->assertArrayNotHasKey('offline', $body[0]);
    }

    public function testOfflineResourcesIndexController_Allow_AdminHasRow_PopulatedObject(): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        $this->logInAs($user);

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body[0]['offline']);
        $this->assertSame($user->get('id'), $body[0]['offline']['user_id']);
        $this->assertSame($resource->get('id'), $body[0]['offline']['foreign_key']);
        $this->assertSame(OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $body[0]['offline']['foreign_model']);
    }

    public function testOfflineResourcesIndexController_Allow_UserHasRow_PopulatedObject(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        $this->logInAs($user);

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body[0]['offline']);
        $this->assertSame($user->get('id'), $body[0]['offline']['user_id']);
        $this->assertSame($resource->get('id'), $body[0]['offline']['foreign_key']);
        $this->assertSame(OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $body[0]['offline']['foreign_model']);
    }

    public function testOfflineResourcesIndexController_Allow_NoUserRow_NullOfflineKey(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertNull($body[0]['offline']);
    }

    public function testOfflineResourcesIndexController_Deny_ContainRequested_NullOfflineKey(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);
        // No seedRbacAllow — default-deny for the user role.

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('offline', $body[0]);
        $this->assertNull($body[0]['offline']);
    }

    public function testOfflineResourcesIndexController_Deny_ContainRequested_NullEvenWhenRowExists(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        $this->logInAs($user);
        // Row exists BUT the user is not RBAC-allowed to see it via the contain.

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('offline', $body[0]);
        $this->assertNull($body[0]['offline']);
    }

    public function testOfflineResourcesIndexController_FeatureDisabled_ContainRequested_NullOfflineKey(): void
    {
        // Remove the settings row seeded by setUp — feature is now disabled.
        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $offlineModeSettingsTable */
        $offlineModeSettingsTable = OfflineModeSettingFactory::make()->getTable();
        $offlineModeSettingsTable->deleteAll(['property' => $offlineModeSettingsTable->getProperty()]);

        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        $this->logInAs($user);
        // RBAC Allow + row exists, but feature is off — payload still suppressed.

        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('offline', $body[0]);
        $this->assertNull($body[0]['offline']);
    }

    public function testOfflineResourcesIndexController_InvalidSettingsValue_ReturnOfflineKeyAsNull(): void
    {
        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $offlineModeSettingsTable */
        $offlineModeSettingsTable = OfflineModeSettingFactory::make()->getTable();
        $offlineModeSettingsTable->deleteAll(['property' => $offlineModeSettingsTable->getProperty()]);
        // invalid settings value
        OfflineModeSettingFactory::make()->setField('value', 'not-an-array')->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $this->seedRbacAllow($user->get('role_id'));
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();

        $this->logInAs($user);
        $this->getJson('/resources.json?contain[offline]=1');

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('offline', $body[0]);
        $this->assertNull($body[0]['offline']);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function seedRbacAllow(string $roleId): void
    {
        $action = ActionFactory::make()
            ->name(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW)
            ->persist();

        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $roleId)
            ->allow()
            ->persist();
    }
}
