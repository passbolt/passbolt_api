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
class OfflineModeGroupsUpdateControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testOfflineModeGroupsUpdateController_Success_WipesOfflineItemForRemovedMember(): void
    {
        /** @var \App\Model\Entity\User $manager */
        $manager = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $member */
        $member = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$manager])
            ->withGroupsUsersFor([$member])
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$manager, $member])
            ->persist();
        OfflineItemFactory::make()->setUser($manager)->setResource($resource)->persist();
        $memberOfflineItem = OfflineItemFactory::make()->setUser($member)->setResource($resource)->persist();

        $this->logInAs($manager);
        $this->putJson("/groups/{$group->id}.json", [
            'groups_users' => [
                ['id' => $group->groups_users[1]->get('id'), 'delete' => true],
            ],
        ]);
        $this->assertSuccess();

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNull(
            OfflineItemFactory::find()->where(['id' => $memberOfflineItem->get('id')])->first(),
        );
    }
}
