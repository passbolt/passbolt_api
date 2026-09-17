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
namespace Passbolt\Edition\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Edition\Middleware\EditionDowngradeDisabledMiddleware;
use Passbolt\Edition\Notification\Email\EditionDowngradeEmailRedactor;
use Passbolt\Edition\Service\EditionGetService;
use Passbolt\Edition\Service\EditionSetService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\Subscription\Test\SubscriptionFactory;

/**
 * @covers \Passbolt\Edition\Command\EditionDowngradeCommand
 */
class EditionDowngradeCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailQueueTrait;
    use LocatorAwareTrait;
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    public function testEditionDowngradeCommand_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        [$admin, $otherAdmin1, $otherAdmin2] = UserFactory::make(5)->admin()->active()->persist();
        // Disabled admins and non-admins must not be notified.
        /** @var \App\Model\Entity\User $disabledAdmin */
        $disabledAdmin = UserFactory::make()->admin()->active()->disabled()->persist();
        /** @var \App\Model\Entity\User $regularUser */
        $regularUser = UserFactory::make()->user()->active()->persist();
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        $this->exec("passbolt edition_downgrade -u {$admin->username}", ['y']);

        $this->assertExitSuccess();
        $this->assertOutputContains('Passbolt was downgraded to the CE edition.');
        $this->assertSame(0, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertSame(0, $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings')->find()->count());
        $this->assertFalse((new EditionGetService())->get()->isPro());
        // The edition row's modified_by must point at the admin resolved from
        // --username, not at the mock UAC used in the setToPro seed above.
        $editionRow = $this->fetchTable('Passbolt/Edition.EditionOrganization')->find()->firstOrFail();
        $this->assertSame($admin->id, $editionRow->get('modified_by'));
        // One downgrade notification per active admin except the operator.
        $this->assertEmailQueueCount(4);
        foreach ([$otherAdmin1, $otherAdmin2] as $recipient) {
            $this->assertEmailIsInQueue([
                'email' => $recipient->username,
                'template' => EditionDowngradeEmailRedactor::TEMPLATE,
            ]);
        }
        $this->assertEmailWithRecipientIsInNotQueue($admin->username);
        $this->assertEmailWithRecipientIsInNotQueue($disabledAdmin->username);
        $this->assertEmailWithRecipientIsInNotQueue($regularUser->username);
        $this->assertEmailInBatchContains(
            [
                $admin->profile->full_name . ' downgraded the instance to Community Edition',
                'Your Passbolt instance was downgraded from Pro to Community Edition',
            ],
            $otherAdmin1->username,
        );
    }

    public function testEditionDowngradeCommand_AbortsCleanly_WhenAlreadyOnCe(): void
    {
        // Edition flag absent → defaults to CE. The conflict from the service
        // is caught and turned into a successful exit so re-runs don't fail.
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->exec("passbolt edition_downgrade -u {$admin->username}", ['y']);

        $this->assertExitSuccess();
        $this->assertErrorContains('The instance is already on CE.');
    }

    public function testEditionDowngradeCommand_AbortsWhenUserDeclinesConfirmation(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        SubscriptionFactory::make()->persist();
        MfaPoliciesSettingFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        $this->exec("passbolt edition_downgrade -u {$admin->username}", ['n']);

        $this->assertExitSuccess();
        $this->assertOutputContains('Aborting...');
        // Data and edition flag are untouched.
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertSame(1, $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings')->find()->count());
        $this->assertTrue((new EditionGetService())->get()->isPro());
    }

    public function testEditionDowngradeCommand_Error_MissingUsernameOption(): void
    {
        $this->exec('passbolt edition_downgrade');

        $this->assertExitError();
    }

    public function testEditionDowngradeCommand_Error_InvalidEmailFormat(): void
    {
        $this->exec('passbolt edition_downgrade -u not-an-email');

        $this->assertExitError();
        $this->assertErrorContains('The username should be a valid email.');
    }

    public function testEditionDowngradeCommand_Error_UserNotFound(): void
    {
        $this->exec('passbolt edition_downgrade -u ghost@passbolt.com');

        $this->assertExitError();
        $this->assertErrorContains('No active admins were found.');
    }

    public function testEditionDowngradeCommand_Error_UserIsNotAdmin(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();

        $this->exec("passbolt edition_downgrade -u {$user->username}");

        $this->assertExitError();
        $this->assertErrorContains('No active admins were found.');
    }

    public function testEditionDowngradeCommand_Error_UserIsDisabled(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->disabled()->persist();

        $this->exec("passbolt edition_downgrade -u {$admin->username}");

        $this->assertExitError();
        $this->assertErrorContains('No active admins were found.');
    }

    public function testEditionDowngradeCommand_Error_UserIsDeleted(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->setField('deleted', true)->persist();

        $this->exec("passbolt edition_downgrade -u {$admin->username}");

        $this->assertExitError();
        $this->assertErrorContains('No active admins were found.');
    }

    public function testEditionDowngradeCommand_Disabled_AbortsWhenFlagSet(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        SubscriptionFactory::make()->persist();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());

        Configure::write(EditionDowngradeDisabledMiddleware::PASSBOLT_SECURITY_EDITION_DOWNGRADE_DISABLED, true);

        $this->exec("passbolt edition_downgrade -u {$admin->username}");

        $this->assertExitError();
        $this->assertErrorContains('Edition downgrade is disabled.');
        // No DB writes: subscription row + PRO edition flag intact.
        $this->assertSame(1, $this->fetchTable('Passbolt/Subscription.Subscriptions')->find()->count());
        $this->assertTrue((new EditionGetService())->get()->isPro());
    }
}
