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
namespace Passbolt\OfflineMode\Test\TestCase\Controller\Share;

use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsSecretDeleteListener
 */
class OfflineModeShareControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testOfflineModeShareController_Success_WipesOfflineItemForUserWhoLostAccess(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $viewer */
        $viewer = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$owner, $viewer])
            ->withSecretsFor([$owner, $viewer])
            ->persist();
        OfflineItemFactory::make()->setUser($owner)->setResource($resource)->persist();
        $viewerOfflineItem = OfflineItemFactory::make()->setUser($viewer)->setResource($resource)->persist();
        $viewerPermission = PermissionFactory::find()
            ->where([
                'aco_foreign_key' => $resource->get('id'),
                'aro_foreign_key' => $viewer->get('id'),
            ])
            ->firstOrFail();

        $this->logInAs($owner);
        $this->putJson("/share/resource/{$resource->id}.json", [
            'permissions' => [
                ['id' => $viewerPermission->get('id'), 'delete' => true],
            ],
        ]);
        $this->assertSuccess();

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNull(
            OfflineItemFactory::find()->where(['id' => $viewerOfflineItem->get('id')])->first(),
        );
    }
}
