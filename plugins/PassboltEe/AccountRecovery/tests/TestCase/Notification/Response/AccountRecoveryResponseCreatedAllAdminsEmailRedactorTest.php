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

namespace Passbolt\AccountRecovery\Test\TestCase\Notification\Response;

use App\Test\Factory\UserFactory;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseCreatedAllAdminsEmailRedactor;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\Locale\LocalePlugin;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseCreatedAllAdminsEmailRedactor
 */
class AccountRecoveryResponseCreatedAllAdminsEmailRedactorTest extends TestCase
{
    use TruncateDirtyTables;

    private AccountRecoveryResponseCreatedAllAdminsEmailRedactor $redactor;

    public function setUp(): void
    {
        parent::setUp();
        $this->redactor = new AccountRecoveryResponseCreatedAllAdminsEmailRedactor();
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

    private function makeResponse(
        string $requesterId,
        string $actingUserId,
        string $status = AccountRecoveryResponse::STATUS_APPROVED,
    ): AccountRecoveryResponse {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::make()->withUser($requesterId)->persist();
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response */
        $response = AccountRecoveryResponseFactory::make()
            ->setField('modified_by', $actingUserId)
            ->setField('account_recovery_request_id', $request->id)
            ->setField('status', $status)
            ->persist();
        $response->account_recovery_request = $request;

        return $response;
    }

    private function collectRecipients(AccountRecoveryResponse $response): array
    {
        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('Foo', $response);
        $collection = $this->redactor->onSubscribedEvent($event);
        $recipients = [];
        foreach ($collection->getEmails() as $email) {
            $recipients[] = $email->getRecipient();
        }

        return $recipients;
    }

    public function testResponseCreatedAllAdmins_LiteralAdminsReceiveEmail(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $otherAdmin] = $admins;

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertContains($otherAdmin->username, $recipients);
        $this->assertNotContains($actingAdmin->username, $recipients);
        $this->assertCount(1, $recipients);
    }

    public function testResponseCreatedAllAdmins_RbacUserWithViewActionReceivesEmail_Approved(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $otherAdmin] = $admins;
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $rbacViewer->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id, AccountRecoveryResponse::STATUS_APPROVED);
        $recipients = $this->collectRecipients($response);

        $this->assertEmpty(array_diff([$otherAdmin->username, $rbacViewer->username], $recipients));
        $this->assertNotContains($actingAdmin->username, $recipients);
        $this->assertCount(2, $recipients);
    }

    public function testResponseCreatedAllAdmins_RbacUserWithViewActionReceivesEmail_Rejected(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $otherAdmin] = $admins;
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $rbacViewer->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id, AccountRecoveryResponse::STATUS_REJECTED);
        $recipients = $this->collectRecipients($response);

        $this->assertEmpty(array_diff([$otherAdmin->username, $rbacViewer->username], $recipients));
        $this->assertNotContains($actingAdmin->username, $recipients);
        $this->assertCount(2, $recipients);
    }

    public function testResponseCreatedAllAdmins_ActingRbacUserExcluded(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $literalAdmin */
        $literalAdmin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $actingRbacViewer */
        $actingRbacViewer = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $actingRbacViewer->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingRbacViewer->id);
        $recipients = $this->collectRecipients($response);

        $this->assertNotContains($actingRbacViewer->username, $recipients);
        $this->assertContains($literalAdmin->username, $recipients);
        $this->assertCount(1, $recipients);
    }

    public function testResponseCreatedAllAdmins_DisabledUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $activeAdmin] = $admins;
        /** @var \App\Model\Entity\User $disabledAdmin */
        $disabledAdmin = UserFactory::make()->admin()->disabled()->persist();
        /** @var \App\Model\Entity\User $disabledRbacViewer */
        $disabledRbacViewer = UserFactory::make()->disabled()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $disabledRbacViewer->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertContains($activeAdmin->username, $recipients);
        $this->assertNotContains($disabledAdmin->username, $recipients);
        $this->assertNotContains($disabledRbacViewer->username, $recipients);
    }

    public function testResponseCreatedAllAdmins_DeletedAndInactiveUsersExcluded(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $activeAdmin] = $admins;
        UserFactory::make()->admin()->deleted()->persist();
        UserFactory::make()->admin()->inactive()->persist();
        /** @var \App\Model\Entity\User $deletedRbacViewer */
        $deletedRbacViewer = UserFactory::make()->deleted()->persist();
        UserFactory::make()->inactive()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $deletedRbacViewer->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertSame([$activeAdmin->username], $recipients);
    }

    public function testResponseCreatedAllAdmins_RbacWithDenyControlFunctionExcluded(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $denied */
        $denied = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $denied->role_id)
            ->setField('control_function', Rbac::CONTROL_FUNCTION_DENY)
            ->persist();
        /** @var \App\Model\Entity\User $actingAdmin */
        $actingAdmin = UserFactory::make()->admin()->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertContains($admin->username, $recipients);
        $this->assertNotContains($denied->username, $recipients);
    }

    public function testResponseCreatedAllAdmins_RbacOnDifferentActionExcluded(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $actingAdmin */
        $actingAdmin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $otherActionUser */
        $otherActionUser = UserFactory::make()->persist();
        $otherAction = ActionFactory::make()->name('AccountRecoveryResponsesCreate.post')->persist();
        RbacFactory::make()->setAction($otherAction)->setField('role_id', $otherActionUser->role_id)->persist();

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertNotContains($otherActionUser->username, $recipients);
    }

    public function testResponseCreatedAllAdmins_ReturnsAdminsOnlyWhenRbacsAssociationMissing(): void
    {
        /** @var \App\Model\Entity\User $requester */
        $requester = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();
        [$actingAdmin, $otherAdmin] = $admins;
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $rbacViewer->role_id)->persist();

        TableRegistry::getTableLocator()->get('Roles')->associations()->remove('Rbacs');

        $response = $this->makeResponse($requester->id, $actingAdmin->id);
        $recipients = $this->collectRecipients($response);

        $this->assertContains($otherAdmin->username, $recipients);
        $this->assertNotContains($rbacViewer->username, $recipients);
        $this->assertCount(1, $recipients);
    }
}
