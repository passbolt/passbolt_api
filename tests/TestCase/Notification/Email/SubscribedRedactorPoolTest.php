<?php
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

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use App\Notification\Email\CollectSubscribedEmailRedactorEvent;
use App\Notification\Email\EmailSubscriptionManager;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SubscribedRedactorPoolTest extends TestCase
{
    use SubscribedEmailRedactorMockTrait;

    /**
     * @var AbstractSubscribedEmailRedactorPool
     */
    private $sut;

    /**
     * @var SubscribedEmailRedactorInterface[]|MockObject[]
     */
    private $subscribedRedactorsMock;

    public function setUp()
    {
        $this->subscribedRedactorsMock = [$this->createMock(SubscribedEmailRedactorInterface::class)];

        $this->sut = new class ($this->subscribedRedactorsMock) extends AbstractSubscribedEmailRedactorPool
        {
            /**
             * @var array
             */
            private $subscribedRedactors;

            public function __construct(array $subscribedRedactors)
            {
                $this->subscribedRedactors = $subscribedRedactors;
            }

            public function getSubscribedRedactors()
            {
                return $this->subscribedRedactors;
            }
        };
        parent::setUp();
    }

    public function testThatIsSubscribedToCollectSubscribedEmailRedactorEvent()
    {
        $this->assertEquals(
            [CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this->sut],
            $this->sut->implementedEvents()
        );
    }

    public function testThatInvokeCallSubscribe()
    {
        $this->assertTrue(is_callable($this->sut));

        $event = CollectSubscribedEmailRedactorEvent::create($this->createMock(EmailSubscriptionManager::class));

        foreach ($this->subscribedRedactorsMock as $subscribedEmailRedactorMock) {
            $subscribedEmailRedactorMock->expects($this->once())
                ->method('subscribe')
                ->with($event);
        }

        call_user_func($this->sut, $event);
    }

    public function testThatSubscribeAllRedactorsWhenInvoked()
    {
        $event = CollectSubscribedEmailRedactorEvent::create($this->createMock(EmailSubscriptionManager::class));

        foreach ($this->subscribedRedactorsMock as $subscribedEmailRedactorMock) {
            $subscribedEmailRedactorMock->expects($this->once())
                ->method('subscribe')
                ->with($event);
        }

        $this->sut->subscribe($event);
    }
}
