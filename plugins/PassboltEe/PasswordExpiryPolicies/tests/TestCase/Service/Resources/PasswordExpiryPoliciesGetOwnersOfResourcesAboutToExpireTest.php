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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Service\Resources;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\Exception\StopException;
use Cake\I18n\FrozenTime;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiryPolicies\PasswordExpiryPoliciesPlugin;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

class PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireTest extends AppTestCase
{
    use EmailNotificationSettingsTestTrait;

    public PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService(
            new PasswordExpiryPoliciesGetSettingsService()
        );
        $this->loadPlugins([
            PasswordExpiryPlugin::class => [],
            PasswordExpiryPoliciesPlugin::class => [],
        ]);
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        unset($this->service);
        parent::tearDown();
    }

    public function emailSettingProvider(): array
    {
        return [
            [true, true, 4],
            [true, false, 2],
            [false, true, 2],
            [false, false, 'Password expiry email notifications are disabled.'],
        ];
    }

    /**
     * @dataProvider emailSettingProvider
     */
    public function testPasswordExpiryPoliciesNotifyAboutExpiredResourcesService_Email_Notification(
        bool $notifyIfAboutToExpire,
        bool $notifyIfExpiresToday,
        $expectedResult
    ) {
        $this->markTestSkipped('This feature is not implemented yet');
        $notifyInDays = rand(1, 100);
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()
            ->setField('value.expiry_notification', $notifyInDays)
            ->persist();
        $this->setEmailNotificationSettings([
            'send.password.aboutToExpire' => $notifyIfAboutToExpire,
            'send.password.expire' => $notifyIfExpiresToday,
        ]);
        if (is_string($expectedResult)) {
            $this->expectExceptionMessage($expectedResult);
            $this->expectException(StopException::class);
        }

        // User 0 will not receive notifications, because he is not owner
        // Users 1 and 2 will receive notifications
        // Users 3 and 4 will not receive notifications because their associated resource expires in 1 day
        [$user0, $user1, $user2, $user3, $user4] = UserFactory::make(6)->user()->persist();

        [$resourceExpiringTodaySharedWithUser0, $resourceExpiringTodaySharedWithUser1, $resourceExpiringTodaySharedWithUser2] = ResourceFactory::make(3)
            ->expired(FrozenTime::now())
            ->persist();
        [$resourceExpiringTomorrowSharedWithUser3, $resourceExpiringTomorrowSharedWithUser4] = ResourceFactory::make(2)
            ->expired(FrozenTime::today()->addDays($notifyInDays))
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTodaySharedWithUser0)
            ->aroUser($user0)
            ->typeRead()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTodaySharedWithUser0)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$user0]))
            ->typeUpdate()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTodaySharedWithUser1)
            ->aroUser($user1)
            ->typeOwner()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTodaySharedWithUser2)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$user2]))
            ->typeOwner()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTomorrowSharedWithUser3)
            ->aroUser($user3)
            ->typeOwner()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringTomorrowSharedWithUser4)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$user4]))
            ->typeOwner()
            ->persist();

        $results = $this->service
            ->notifyUsers()
            ->find('list');
        $this->assertSame($expectedResult, $results->count());
        if ($notifyIfExpiresToday) {
            $this->assertArrayHasKey($user1->id, $results->toArray());
            $this->assertArrayHasKey($user2->id, $results->toArray());
        }
        if ($notifyIfAboutToExpire) {
            $this->assertArrayHasKey($user3->id, $results->toArray());
            $this->assertArrayHasKey($user4->id, $results->toArray());
        }
    }

    public function testPasswordExpiryPoliciesNotifyAboutExpiredResourcesService_ResourcesExpiredOrExpiringWithinDays()
    {
        $this->markTestSkipped('This feature is not implemented yet');
        /** @var \App\Model\Entity\Resource[] $resources */
        $resources = ResourceFactory::make([
            ['expired' => null], // to be ignored
            ['expired' => FrozenTime::now()->subDays(1)], // to be ignored, it is expired
            ['expired' => FrozenTime::now()->subMinutes(2)], // expires today
            ['expired' => FrozenTime::now()], // expires today
            ['expired' => FrozenTime::now()->addDays(1)], // expires exactly in 1 day
            ['expired' => FrozenTime::now()->addDays(1)->addSeconds(1)], // expires in 1 day
            ['expired' => FrozenTime::now()->addDays(1)->addMinutes(2)], // expires in 1 day
            ['expired' => FrozenTime::now()->addDays(2)], // expired in 2 days
            ['expired' => FrozenTime::now()->addDays(2)->addMinutes(2)], // expires in 2 days
            ['expired' => FrozenTime::now()->addDays(3)], // expired in 3 days
        ])->persist();

        // Find resources expiring today or today
        $results = $this->service->findResourcesExpiringTodayOrInNDays(0)->find('list');
        $this->assertSame(2, $results->count());
        $this->assertArrayHasKey($resources[2]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[3]['id'], $results->toArray());

        // Find resources expiring tomorrow or today
        $results = $this->service->findResourcesExpiringTodayOrInNDays(1)->find('list');
        $this->assertSame(5, $results->count());
        $this->assertArrayHasKey($resources[2]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[3]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[4]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[5]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[6]['id'], $results->toArray());

        // Find resources expiring after tomorrow or today
        $results = $this->service->findResourcesExpiringTodayOrInNDays(2)->find('list');
        $this->assertSame(4, $results->count());
        $this->assertArrayHasKey($resources[2]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[3]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[7]['id'], $results->toArray());
        $this->assertArrayHasKey($resources[8]['id'], $results->toArray());
    }
}
