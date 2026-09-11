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
namespace Passbolt\OfflineMode\Test\TestCase\Controller\Groups;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsSecretDeleteListener
 */
class OfflineModeGroupsDeleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testOfflineModeGroupsDeleteController_Success_WipesOfflineItemsForAllMembers(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $userB */
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$userA, $userB])
            ->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resource)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resource)->persist();

        $this->logInAs($admin);
        $this->deleteJson("/groups/{$group->id}.json");
        $this->assertSuccess();

        $this->assertSame(0, OfflineItemFactory::count());
    }
}
