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
 * @since         4.10.0
 */
namespace Passbolt\Subscription\Test\TestCase\Service\Healthcheck\Application;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\I18n\FrozenDate;
use Cake\TestSuite\TestCase;
use Passbolt\Subscription\Service\Healthcheck\Application\SubscriptionKeyApplicationHealthcheck;

/**
 * @covers \App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck
 */
class SubscriptionKeyApplicationHealthcheckTest extends TestCase
{
    public function testSubscriptionKeyApplicationHealthcheck_Success(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => null,
            'expiry' => (new FrozenDate())->addDays(31),
            'allowedUsers' => 10,
            'currentUsers' => 1,
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertTrue($service->isPassed());
        $this->assertTextEquals(__('Subscription is valid and up to date.'), $service->getSuccessMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_Invalid(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => 'Exception message',
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $this->assertTextEquals(__('Subscription invalid/expired ({0}).', $array['error']), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_Expired(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => null,
            'expiry' => (new FrozenDate())->subDays(1),
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $this->assertTextEquals(__('Subscription expired ({0}).', $array['expiry']->toFormattedDateString()), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_ExpiresSoon(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => null,
            'expiry' => (new FrozenDate())->addDays(25),
            'allowedUsers' => 10,
            'currentUsers' => 1,
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $service->level());
        $this->assertTextEquals(__('Subscription will expire in {0} days.', $array['expiry']->diffInDays()), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_TooManyUsers(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => null,
            'expiry' => (new FrozenDate())->addDays(31),
            'allowedUsers' => 10,
            'currentUsers' => 11,
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $message = __(
            'Subscription user count has been exceeded ({0}/{1}).',
            $array['currentUsers'],
            $array['allowedUsers']
        );
        $this->assertTextEquals($message, $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_AlmostTooManyUsers(): void
    {
        $service = $this->getMockBuilder(SubscriptionKeyApplicationHealthcheck::class)->onlyMethods(['checkSubscription'])->getMock();
        $array = [
            'error' => null,
            'expiry' => (new FrozenDate())->addDays(31),
            'allowedUsers' => 10,
            'currentUsers' => 9,
        ];
        $service->method('checkSubscription')->willReturn($array);
        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $service->level());
        $message = __(
            'Subscription soon exceeds user count ({0}/{1}).',
            $array['currentUsers'],
            $array['allowedUsers']
        );
        $this->assertTextEquals($message, $service->getFailureMessage());
    }
}
