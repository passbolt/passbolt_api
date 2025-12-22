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
 * @since         4.4.0
 */
namespace App\Test\TestCase\Controller\Users;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\Role;
use App\Notification\Email\Redactor\User\UserAdminRoleRevokedEmailRedactor;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * @covers \App\Controller\Users\UsersEditController
 */
class UsersEditAdminRoleRevokedControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();

        RoleFactory::make()->guest()->persist();
        // Enable event tracking for emails
        EventManager::instance()->setEventList(new EventList());
        // Mock user agent and IP
        $this->mockUserAgent('PHPUnit');
        $this->mockUserIp();
    }

    public function testUsersEditAdminRoleRevokedController_NotificationEnabled(): void
    {
        $jane = UserFactory::make([
            'username' => 'jane@passbolt.test',
            'profile' => [
                'first_name' => 'Jane',
                'last_name' => "O'Keefe",
            ],
        ])->admin()->persist();
        $john = UserFactory::make(['username' => 'john@passbolt.test'])->admin()->persist();
        $ada = UserFactory::make(['username' => 'ada@passbolt.test'])->admin()->persist();
        UserFactory::make()->user()->persist();
        $userRole = RoleFactory::find()->where(['name' => Role::USER])->firstOrFail();

        // John(admin) downgrade Jane's role to User
        $this->logInAs($john);
        $data = [
            'id' => $jane->id,
            'role_id' => $userRole->id,
        ];
        $this->putJson("/users/{$jane->id}.json", $data);

        $this->assertSuccess();
        $this->assertSame(Role::USER, $this->_responseJsonBody->role->name);
        // Email assertions
        $this->assertEventFired(UsersEditController::EVENT_USER_AFTER_UPDATE);
        $this->assertEmailQueueCount(3);
        // Assert role revoked email sent to other users
        $emailText = Purifier::clean(sprintf('%s\'s admin role has been revoked', $jane->profile->full_name));
        foreach ([$john, $ada] as $admin) {
            $this->assertEmailInBatchContains($emailText, $admin->username, '', false);
            $this->assertEmailInBatchContains(
                Router::url('/app/users/view/' . $jane->id, true),
                $admin->username
            );
        }
        // Make sure role changed email sent to the user whose role got changed
        $this->assertEmailInBatchContains('Your role has been updated', $jane->username, '', false);
    }

    public function testUsersEditAdminRoleRevokedController_NotificationDisabled(): void
    {
        $jane = UserFactory::make(['username' => 'jane@passbolt.test'])->admin()->persist();
        $john = UserFactory::make(['username' => 'john@passbolt.test'])->admin()->persist();
        UserFactory::make(['username' => 'ada@passbolt.test'])->admin()->persist();
        UserFactory::make()->user()->persist();
        $userRole = RoleFactory::find()->where(['name' => Role::USER])->firstOrFail();
        // Disable notification
        Configure::write(UserAdminRoleRevokedEmailRedactor::CONFIG_KEY_EMAIL_ENABLED, false);

        // John(admin) downgrade Jane's role to User
        $this->logInAs($john);
        $data = [
            'id' => $jane->id,
            'role_id' => $userRole->id,
        ];
        $this->putJson("/users/{$jane->id}.json", $data);

        $this->assertSuccess();
        $this->assertSame(Role::USER, $this->_responseJsonBody->role->name);
        $this->assertEmailQueueCount(1);
        $emailText = Purifier::clean(sprintf('%s changed your role to %s', $john->profile->full_name, $userRole->name));
        $this->assertEmailInBatchContains($emailText, $jane->username, '', false);
    }

    public function testUsersEditAdminRoleRevokedController_NotifyUserWhoseRoleIsDowngraded(): void
    {
        $jane = UserFactory::make(['username' => 'jane@passbolt.test'])
            ->admin()
            ->with('Profiles', ['first_name' => 'Jane', 'last_name' => 'Doe'])
            ->persist();
        /** @var \App\Model\Entity\User $john */
        $john = UserFactory::make(['username' => 'john@passbolt.test'])->admin()->persist();
        $ada = UserFactory::make(['username' => 'ada@passbolt.test'])->admin()->persist();
        UserFactory::make()->user()->persist();
        $customRole = RoleFactory::make(['name' => 'custom role'])->persist();
        // Enable sending email to user
        Configure::write(UserAdminRoleRevokedEmailRedactor::CONFIG_KEY_SEND_USER_EMAIL, true);

        // John(admin) downgrade Jane's role to User
        $this->logInAs($john);
        $data = [
            'id' => $jane->id,
            'role_id' => $customRole->id,
        ];
        $this->putJson("/users/{$jane->id}.json", $data);

        $this->assertSuccess();
        $this->assertSame('custom role', $this->_responseJsonBody->role->name);
        // Email assertions
        $this->assertEventFired(UsersEditController::EVENT_USER_AFTER_UPDATE);
        $this->assertEmailQueueCount(3);
        $userFullName = Purifier::clean($jane->profile->first_name . ' ' . $jane->profile->last_name);
        $title = Purifier::clean(sprintf('%s\'s admin role has been revoked', $jane->profile->full_name));
        foreach ([$john, $ada] as $admin) {
            $this->assertEmailInBatchContains($title, $admin->username, '', false);
            $this->assertEmailInBatchContains(
                [
                    "{$john->profile->full_name} changed the role of {$userFullName} to custom role.",
                    Router::url('/app/users/view/' . $jane->id, true),
                ],
                $admin->username
            );
        }
        // Role changed notification sent to the user
        $this->assertEmailInBatchContains('Your role has been updated', $jane->username);
        $this->assertEmailInBatchContains('You can no longer perform administration tasks.', $jane->username);
    }
}
