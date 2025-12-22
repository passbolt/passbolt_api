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

    /**
     * Admins and uses with a role able to view requests via RBACS should receive notification
     */
    public function testAccountRecoveryRequestCreatedAdminEmailRedactor_Admins_And_Roles_With_Rbacs_Should_Be_Notified()
    {
        // Create two admin users who should always receive notifications
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(2)->admin()->persist();

        // Create a non-admin user who will receive notifications via RBAC permission
        /** @var \App\Model\Entity\User $accountRecoveryRequestsViewer */
        $accountRecoveryRequestsViewer = UserFactory::make()->persist();

        // Create the AccountRecoveryRequestsView action and grant permission to this user's role
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $accountRecoveryRequestsViewer->role_id)->persist();

        // Create the user who initiates the account recovery request
        $requester = UserFactory::make()->persist();

        // Create and persist the account recovery request
        $request = AccountRecoveryRequestFactory::make()->withUser($requester->get('id'))->persist();

        // Trigger the email redactor with the request event
        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('Foo', $request);
        $emailCollection = $this->redactor->onSubscribedEvent($event);

        // Verify all expected recipients receive the notification:
        // - Both admin users
        // - The non-admin user with RBAC view permission
        $expectedRecipients = [$admins[0]->username, $admins[1]->username, $accountRecoveryRequestsViewer->username];
        $recipients = [];
        foreach ($emailCollection->getEmails() as $email) {
            $recipients[] = $email->getRecipient();
        }

        // Assert all expected recipients are in the email collection
        $this->assertEmpty(array_diff($expectedRecipients, $recipients));
        $this->assertCount(3, $emailCollection->getEmails());
    }
}
