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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Test\TestCase\Service;

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Edition\Service\EditionDowngradeService;
use Passbolt\Edition\Service\EditionGetService;
use Passbolt\Edition\Service\EditionSetService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\Subscription\Test\SubscriptionFactory;

/**
 * @covers \Passbolt\Edition\Service\EditionDowngradeService
 */
class EditionDowngradeServiceTest extends TestCase
{
    use LocatorAwareTrait;
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    public function setUp(): void
    {
        parent::setUp();
        EventManager::instance()->setEventList(new EventList());
    }

    public function testEditionDowngradeService_Downgrade_HappyPath(): void
    {
        // Pre-conditions: subscription row + residual PRO data + edition flag = pro.
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());
        $this->assertTrue((new EditionGetService())->get()->isPro());

        (new EditionDowngradeService())->downgrade($this->mockAdminAccessControl());

        // Subscription row gone (count via the scoped table — the factory's
        // count() bypasses beforeFind and would include unrelated rows).
        $this->assertSame(0, $this->subscriptionRowCount());
        // Residual PRO data gone (via the registry-driven runner).
        $this->assertSame(0, $this->mfaPoliciesRowCount());
        // Edition flag flipped to CE.
        $this->assertFalse((new EditionGetService())->get()->isPro());
        // Event fired
        $this->assertEventFired(EditionDowngradeService::EVENT_EDITION_DOWNGRADED);
    }

    public function testEditionDowngradeService_Downgrade_RollsBackWhenStepThrows(): void
    {
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        // Non-admin UAC → SubscriptionKeyDeleteService::delete throws
        // ForbiddenException as the very first step. Transaction rolls back.
        try {
            (new EditionDowngradeService())->downgrade($this->mockUserAccessControl());
            $this->fail('Expected ForbiddenException');
        } catch (ForbiddenException) {
            // expected
        }

        // Nothing changed.
        $this->assertSame(1, $this->subscriptionRowCount());
        $this->assertSame(1, $this->mfaPoliciesRowCount());
        $this->assertTrue((new EditionGetService())->get()->isPro());
        // Post-commit step never ran.
        $this->assertFalse(
            EventManager::instance()->getEventList()->hasEvent(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
        );
    }

    public function testEditionDowngradeService_Downgrade_StillDispatchesEventWhenNoResidualPluginData(): void
    {
        // Edition is PRO but nothing else: no subscription, no plugin residue.
        // The downgrade still completes and fires the event so administrators
        // get notified of the intent.
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        (new EditionDowngradeService())->downgrade($this->mockAdminAccessControl());

        $this->assertFalse((new EditionGetService())->get()->isPro());
        $this->assertEventFired(EditionDowngradeService::EVENT_EDITION_DOWNGRADED);
    }

    public function testEditionDowngradeService_Downgrade_ThrowsForbiddenWhenUacIsNotAdmin(): void
    {
        // Seed CE state on purpose: the assertIsAdmin guard runs *before* the
        // PRO/CE precondition, so a non-admin UAC must surface as Forbidden
        // even when the instance is already on CE (proving the guard fires
        // first, not ConflictException). No side effects either way.
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();

        $this->expectException(ForbiddenException::class);
        try {
            (new EditionDowngradeService())->downgrade($this->mockUserAccessControl());
        } finally {
            $this->assertSame(1, $this->subscriptionRowCount());
            $this->assertSame(1, $this->mfaPoliciesRowCount());
            $this->assertFalse((new EditionGetService())->get()->isPro());
            $this->assertFalse(
                EventManager::instance()->getEventList()->hasEvent(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
            );
        }
    }

    public function testEditionDowngradeService_Downgrade_ThrowsConflictWhenAlreadyOnCe(): void
    {
        // No edition row written → EditionGetService::get()->isPro() is false.
        // Downgrade only makes sense from PRO; surface as 409.
        $this->expectException(ConflictException::class);
        try {
            (new EditionDowngradeService())->downgrade($this->mockAdminAccessControl());
        } finally {
            // Precondition failed before any work, so nothing was dispatched.
            $this->assertFalse(
                EventManager::instance()->getEventList()->hasEvent(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
            );
        }
    }

    private function subscriptionRowCount(): int
    {
        return $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count();
    }

    private function mfaPoliciesRowCount(): int
    {
        return $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings')->find()->count();
    }
}
