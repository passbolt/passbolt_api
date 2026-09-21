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
namespace Passbolt\Edition\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Passbolt\Edition\Middleware\EditionDowngradeDisabledMiddleware;
use Passbolt\Edition\Notification\Email\EditionDowngradeEmailRedactor;
use Passbolt\Edition\Service\EditionGetService;
use Passbolt\Edition\Service\EditionSetService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\Subscription\Test\SubscriptionFactory;

/**
 * @covers \Passbolt\Edition\Controller\EditionSubscriptionsDeleteController
 */
class EditionSubscriptionsDeleteControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use UserAccessControlTrait;

    private const URL = '/edition/subscription/key.json';

    public function testEditionSubscriptionsDeleteController_Delete_Success(): void
    {
        // Pre-conditions: PRO edition + subscription row + residual PRO data.
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->active()->persist();
        // Disabled admins and non-admins must not be notified.
        /** @var \App\Model\Entity\User $disabledAdmin */
        $disabledAdmin = UserFactory::make()->admin()->active()->disabled()->persist();
        /** @var \App\Model\Entity\User $regularUser */
        $regularUser = UserFactory::make()->user()->active()->persist();

        $operator = $this->logInAsAdmin();
        $this->deleteJson(self::URL);

        $this->assertSuccess();
        $this->assertSame(0, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertSame(0, $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings')->find()->count());
        $this->assertFalse((new EditionGetService())->get()->isPro());
        $this->assertEmailQueueCount(1);
        $this->assertEmailIsInQueue([
            'email' => $otherAdmin->username,
            'template' => EditionDowngradeEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailWithRecipientIsInNotQueue($operator->username);
        $this->assertEmailWithRecipientIsInNotQueue($disabledAdmin->username);
        $this->assertEmailWithRecipientIsInNotQueue($regularUser->username);
        $this->assertEmailInBatchContains(
            [
                $operator->profile->full_name . ' downgraded the instance to Community Edition',
                'Your Passbolt instance was downgraded from Pro to Community Edition',
            ],
            $otherAdmin->username,
        );
    }

    public function testEditionSubscriptionsDeleteController_Delete_Error_AuthenticationRequired(): void
    {
        SubscriptionFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        $this->deleteJson(self::URL);

        $this->assertAuthenticationError();
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
    }

    public function testEditionSubscriptionsDeleteController_Delete_Error_ForbiddenNotAdmin(): void
    {
        SubscriptionFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        $this->logInAsUser();
        $this->deleteJson(self::URL);

        $this->assertForbiddenError('You are not allowed to access this location.');
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertTrue((new EditionGetService())->get()->isPro());
    }

    public function testEditionSubscriptionsDeleteController_Delete_Success_WhenSubscriptionRowMissing(): void
    {
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->active()->persist();

        $operator = $this->logInAsAdmin();
        $this->deleteJson(self::URL);

        $this->assertSuccess();
        $this->assertSame(0, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertFalse((new EditionGetService())->get()->isPro());
        $this->assertEmailQueueCount(1);
        $this->assertEmailIsInQueue([
            'email' => $otherAdmin->username,
            'template' => EditionDowngradeEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailWithRecipientIsInNotQueue($operator->username);
    }

    public function testEditionSubscriptionsDeleteController_Delete_Error_ConflictAlreadyOnCe(): void
    {
        SubscriptionFactory::make()->persist();

        $this->logInAsAdmin();
        $this->deleteJson(self::URL);

        $this->assertError(409, 'The instance is already on CE.');
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
    }

    public function testEditionSubscriptionsDeleteController_Error_EndpointDisabled(): void
    {
        SubscriptionFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());
        Configure::write(EditionDowngradeDisabledMiddleware::PASSBOLT_SECURITY_EDITION_DOWNGRADE_DISABLED, true);

        $this->logInAsAdmin();
        $this->deleteJson(self::URL);

        $this->assertForbiddenError('Edition downgrade is disabled.');
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertTrue((new EditionGetService())->get()->isPro());
    }
}
