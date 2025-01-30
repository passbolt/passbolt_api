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
use App\Test\Factory\UserFactory;
use Cake\I18n\FrozenDate;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Subscription\Model\Dto\SubscriptionKeyDto;
use Passbolt\Subscription\Service\Healthcheck\Application\SubscriptionKeyApplicationHealthcheck;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;

/**
 * @covers \App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck
 */
class SubscriptionKeyApplicationHealthcheckTest extends TestCase
{
    use TruncateDirtyTables;

    public function testSubscriptionKeyApplicationHealthcheck_Success(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $dto = SubscriptionKeyDto::createFromArray([
            'users' => 4,
            'expiry' => FrozenDate::today()->addDays(100)->toDateString(),
        ]);
        $subscriptionService->method('get')->willReturn($dto);
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        UserFactory::make(1)->active()->persist();
        UserFactory::make(4)->inactive()->persist();
        UserFactory::make(4)->active()->deleted()->persist();

        $service->check();

        $this->assertTrue($service->isPassed());
        $this->assertTextEquals(__('Subscription is valid and up to date.'), $service->getSuccessMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_Invalid(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $msg = 'Exception message';
        $subscriptionService->method('get')->willThrowException(new \Exception($msg));
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $this->assertTextEquals(__('Subscription invalid/expired ({0}).', $msg), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_Expired(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $expiry = FrozenDate::yesterday()->toFormattedDateString();
        $dto = SubscriptionKeyDto::createFromArray([
            'users' => 5,
            'expiry' => $expiry,
        ]);
        $subscriptionService->method('get')->willReturn($dto);
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $this->assertTextEquals(__('Subscription expired ({0}).', $expiry), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_ExpiresSoon(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $expiresInDays = 25;
        $dto = SubscriptionKeyDto::createFromArray([
            'users' => 5,
            'expiry' => FrozenDate::today()->addDays($expiresInDays)->toFormattedDateString(),
        ]);
        $subscriptionService->method('get')->willReturn($dto);
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $service->level());
        $this->assertTextEquals(__('Subscription will expire in {0} days.', $expiresInDays), $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_TooManyUsers(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $dto = SubscriptionKeyDto::createFromArray([
            'users' => 2,
            'expiry' => FrozenDate::today()->addDays(100)->toDateString(),
        ]);
        $subscriptionService->method('get')->willReturn($dto);
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        UserFactory::make(3)->active()->persist();

        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $message = __('Subscription user count has been exceeded ({0}/{1}).', 3, 2);
        $this->assertTextEquals($message, $service->getFailureMessage());
    }

    public function testSubscriptionKeyApplicationHealthcheck_Error_AlmostTooManyUsers(): void
    {
        $subscriptionService = $this->getMockBuilder(SubscriptionKeyGetService::class)
            ->onlyMethods(['get'])
            ->getMock();
        $dto = SubscriptionKeyDto::createFromArray([
            'users' => 10,
            'expiry' => FrozenDate::today()->addDays(100)->toDateString(),
        ]);
        $subscriptionService->method('get')->willReturn($dto);
        $service = new SubscriptionKeyApplicationHealthcheck($subscriptionService);

        UserFactory::make(9)->active()->persist();

        $service->check();

        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $service->level());
        $message = __('Subscription soon exceeds user count ({0}/{1}).', 9, 10);
        $this->assertTextEquals($message, $service->getFailureMessage());
    }
}
