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

use App\Model\Table\UsersTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Event\Event;
use Passbolt\OfflineMode\Event\OfflineItemsUserDeleteListener;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsUserDeleteListener
 */
class OfflineItemsUserDeleteListenerTest extends AppTestCase
{
    private OfflineItemsUserDeleteListener $listener;

    public function setUp(): void
    {
        parent::setUp();
        $this->listener = new OfflineItemsUserDeleteListener();
    }

    public function tearDown(): void
    {
        unset($this->listener);
        parent::tearDown();
    }

    public function testOfflineItemsUserDeleteListener_Success_DeletesOnlyThisUsersRows(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make()->setUser($userA)->persist();
        $offlineB = OfflineItemFactory::make()->setUser($userB)->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(UsersTable::EVENT_MODEL_USERS_AFTER_SOFT_DELETE, $userA);
        $this->listener->handleUserAfterSoftDelete($event);

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $offlineB->get('id')])->first(),
        );
    }

    public function testOfflineItemsUserDeleteListener_Success_NoOp_WhenUserHasNoOfflineItems(): void
    {
        $user = UserFactory::make()->user()->active()->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(UsersTable::EVENT_MODEL_USERS_AFTER_SOFT_DELETE, $user);
        $this->listener->handleUserAfterSoftDelete($event);

        $this->assertSame(0, OfflineItemFactory::count());
    }
}
