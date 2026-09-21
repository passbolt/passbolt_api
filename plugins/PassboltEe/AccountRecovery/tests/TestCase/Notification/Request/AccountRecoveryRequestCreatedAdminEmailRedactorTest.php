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
 * @since         5.8.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Notification\Request;

use App\Test\Factory\UserFactory;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\Notification\Request\AccountRecoveryRequestCreatedAdminEmailRedactor;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\Locale\LocalePlugin;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\AccountRecovery\Notification\Request\AccountRecoveryRequestCreatedAdminEmailRedactor
 */
class AccountRecoveryRequestCreatedAdminEmailRedactorTest extends TestCase
{
    use TruncateDirtyTables;

    private AccountRecoveryRequestCreatedAdminEmailRedactor $redactor;

    public function setUp(): void
    {
        parent::setUp();
        $this->redactor = new AccountRecoveryRequestCreatedAdminEmailRedactor();
        $this->loadPlugins([
            LocalePlugin::class => [],
            RbacsPlugin::class => [],
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->redactor);
    }

    private function grantViewActionToRole(string $roleId): void
    {
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $roleId)->persist();
    }

    private function collectRecipients(string $requesterId): array
    {
        $request = AccountRecoveryRequestFactory::make()->withUser($requesterId)->persist();
        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('Foo', $request);
        $collection = $this->redactor->onSubscribedEvent($event);
        $recipients = [];
        foreach ($collection->getEmails() as $email) {
            $recipients[] = $email->getRecipient();
        }

        return $recipients;
    }

    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_AdminsAndRbacViewersNotified(): void
    {
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $this->grantViewActionToRole($rbacViewer->role_id);
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($requester->id);

        $expected = [$admins[0]->username, $admins[1]->username, $rbacViewer->username];
        $this->assertEmpty(array_diff($expected, $recipients));
        $this->assertCount(3, $recipients);
    }

    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_RequesterExcludedWhenAdmin(): void
    {
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->admin()->persist();

        $recipients = $this->collectRecipients($requester->id);

        $this->assertContains($otherAdmin->username, $recipients);
        $this->assertNotContains($requester->username, $recipients);
        $this->assertCount(1, $recipients);
    }

    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_RequesterExcludedWhenRbacViewer(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        $this->grantViewActionToRole($requester->role_id);

        $recipients = $this->collectRecipients($requester->id);

        $this->assertContains($admin->username, $recipients);
        $this->assertNotContains($requester->username, $recipients);
        $this->assertCount(1, $recipients);
    }

    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_DisabledUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $activeAdmin */
        $activeAdmin = UserFactory::make()->admin()->persist();
        UserFactory::make()->admin()->disabled()->persist();
        /** @var \App\Model\Entity\User $disabledRbacViewer */
        $disabledRbacViewer = UserFactory::make()->disabled()->persist();
        $this->grantViewActionToRole($disabledRbacViewer->role_id);
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($requester->id);

        $this->assertSame([$activeAdmin->username], $recipients);
    }

    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_DeletedAndInactiveUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $activeAdmin */
        $activeAdmin = UserFactory::make()->admin()->persist();
        UserFactory::make()->admin()->deleted()->persist();
        UserFactory::make()->admin()->inactive()->persist();
        /** @var \App\Model\Entity\User $deletedRbacViewer */
        $deletedRbacViewer = UserFactory::make()->deleted()->persist();
        $this->grantViewActionToRole($deletedRbacViewer->role_id);
        UserFactory::make()->inactive()->persist();
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($requester->id);

        $this->assertSame([$activeAdmin->username], $recipients);
    }
}
