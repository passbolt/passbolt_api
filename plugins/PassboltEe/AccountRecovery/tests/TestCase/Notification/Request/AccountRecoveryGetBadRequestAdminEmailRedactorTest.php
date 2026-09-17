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
 * @since         5.14.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Notification\Request;

use App\Test\Factory\UserFactory;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\Notification\Request\AccountRecoveryGetBadRequestAdminEmailRedactor;
use Passbolt\Locale\LocalePlugin;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\AccountRecovery\Notification\Request\AccountRecoveryGetBadRequestAdminEmailRedactor
 */
class AccountRecoveryGetBadRequestAdminEmailRedactorTest extends TestCase
{
    use TruncateDirtyTables;

    private AccountRecoveryGetBadRequestAdminEmailRedactor $redactor;

    public function setUp(): void
    {
        parent::setUp();
        $this->redactor = new AccountRecoveryGetBadRequestAdminEmailRedactor();
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

    private function collectRecipients(string $userId): array
    {
        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('Foo', null, [
            'userId' => $userId,
            'requestId' => 'a0000000-0000-0000-0000-000000000000',
            'clientIp' => '1.2.3.4',
        ]);
        $collection = $this->redactor->onSubscribedEvent($event);
        $recipients = [];
        foreach ($collection->getEmails() as $email) {
            $recipients[] = $email->getRecipient();
        }

        return $recipients;
    }

    public function testAccountRecoveryGetBadRequestAdminEmailRedactor_AdminsAndRbacViewersNotified(): void
    {
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $this->grantViewActionToRole($rbacViewer->role_id);
        /** @var \App\Model\Entity\User $targetedUser */
        $targetedUser = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($targetedUser->id);

        $expected = [$admins[0]->username, $admins[1]->username, $rbacViewer->username];
        $this->assertEmpty(array_diff($expected, $recipients));
        $this->assertCount(3, $recipients);
    }

    public function testAccountRecoveryGetBadRequestAdminEmailRedactor_TargetedUserExcludedWhenAdmin(): void
    {
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $targetedAdmin */
        $targetedAdmin = UserFactory::make()->admin()->persist();

        $recipients = $this->collectRecipients($targetedAdmin->id);

        $this->assertContains($otherAdmin->username, $recipients);
        $this->assertNotContains($targetedAdmin->username, $recipients);
        $this->assertCount(1, $recipients);
    }

    public function testAccountRecoveryGetBadRequestAdminEmailRedactor_DisabledUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $activeAdmin */
        $activeAdmin = UserFactory::make()->admin()->persist();
        UserFactory::make()->admin()->disabled()->persist();
        /** @var \App\Model\Entity\User $disabledRbacViewer */
        $disabledRbacViewer = UserFactory::make()->disabled()->persist();
        $this->grantViewActionToRole($disabledRbacViewer->role_id);
        /** @var \App\Model\Entity\User $targetedUser */
        $targetedUser = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($targetedUser->id);

        $this->assertSame([$activeAdmin->username], $recipients);
    }

    public function testAccountRecoveryGetBadRequestAdminEmailRedactor_DeletedAndInactiveUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $activeAdmin */
        $activeAdmin = UserFactory::make()->admin()->persist();
        UserFactory::make()->admin()->deleted()->persist();
        UserFactory::make()->admin()->inactive()->persist();
        /** @var \App\Model\Entity\User $deletedRbacViewer */
        $deletedRbacViewer = UserFactory::make()->deleted()->persist();
        $this->grantViewActionToRole($deletedRbacViewer->role_id);
        UserFactory::make()->inactive()->persist();
        /** @var \App\Model\Entity\User $targetedUser */
        $targetedUser = UserFactory::make()->persist();

        $recipients = $this->collectRecipients($targetedUser->id);

        $this->assertSame([$activeAdmin->username], $recipients);
    }
}
