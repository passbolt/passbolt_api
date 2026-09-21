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
namespace Passbolt\OfflineMode\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsUserDeleteListener
 */
class OfflineModeUsersDeleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testOfflineModeUsersDeleteController_Success_WipesOfflineItemsOfDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $userToDelete */
        $userToDelete = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $survivor */
        $survivor = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make()->setUser($userToDelete)->persist();
        $survivorItem = OfflineItemFactory::make()->setUser($survivor)->persist();

        $this->logInAsAdmin();
        $this->deleteJson("/users/{$userToDelete->id}.json");
        $this->assertSuccess();

        $this->assertSame(1, OfflineItemFactory::count(), 'Only the survivor row should remain');
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $survivorItem->get('id')])->first(),
            "Survivor's offline_items row should be untouched",
        );
    }

    public function testOfflineModeUsersDeleteController_DryRun_DoesNotWipeOfflineItems(): void
    {
        /** @var \App\Model\Entity\User $userToDelete */
        $userToDelete = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make()->setUser($userToDelete)->persist();

        $this->logInAsAdmin();
        $this->deleteJson("/users/{$userToDelete->id}/dry-run.json");
        $this->assertSuccess();

        // Dry-run must not dispatch the soft-delete event — listener stays inert.
        $this->assertSame(1, OfflineItemFactory::count(), 'Dry-run must leave offline_items intact');
    }
}
