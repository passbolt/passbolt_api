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
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsResourceDeleteListener
 */
class OfflineModeResourcesDeleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testOfflineModeResourcesDeleteController_Success_WipesOfflineItemsForDeletedResource(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resourceToDelete */
        $resourceToDelete = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $survivorResource */
        $survivorResource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resourceToDelete)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resourceToDelete)->persist();
        $survivorItem = OfflineItemFactory::make()->setUser($userA)->setResource($survivorResource)->persist();

        $this->logInAs($userA);
        $this->deleteJson("/resources/{$resourceToDelete->id}.json");
        $this->assertSuccess();

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $survivorItem->get('id')])->first(),
        );
    }
}
