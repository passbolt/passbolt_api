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
namespace Passbolt\OfflineMode\Test\TestCase\Event;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Event\Event;
use Passbolt\OfflineMode\Event\OfflineItemsSettingsDeleteListener;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsSettingsDeleteListener
 */
class OfflineItemsSettingsDeleteListenerTest extends AppTestCase
{
    /**
     * @var OfflineItemsSettingsDeleteListener|null
     */
    private ?OfflineItemsSettingsDeleteListener $sut = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new OfflineItemsSettingsDeleteListener();
    }

    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testOfflineItemsSettingsDeleteListener_Success_TruncatesAllRows(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        $resourceA = ResourceFactory::make()->withCreatorAndPermission($userA)->persist();
        $resourceB = ResourceFactory::make()->withCreatorAndPermission($userB)->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resourceA)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resourceB)->persist();
        $this->assertSame(2, OfflineItemFactory::count());

        $this->sut->truncateOfflineItems(
            new Event(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED),
        );

        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsSettingsDeleteListener_Success_EmptyTableNoOp(): void
    {
        $this->assertSame(0, OfflineItemFactory::count());

        $this->sut->truncateOfflineItems(
            new Event(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED),
        );

        $this->assertSame(0, OfflineItemFactory::count());
    }
}
