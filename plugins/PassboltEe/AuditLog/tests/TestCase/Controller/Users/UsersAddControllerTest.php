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
 * @since         3.7.0
 */
namespace Passbolt\AuditLog\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class UsersAddControllerTest extends LogIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * @covers \Passbolt\AuditLog\Controller\UserLogsController::view
     */
    public function testUsersAddSuccess()
    {
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();

        $admin = $this->logInAsAdmin();
        $adminRoleId = $admin->role->id;
        $username = 'ping.fu@passbolt.com';
        $firstName = '傅';
        $lastName = '苹';
        $data = [
            'username' => $username,
            'role_id' => $adminRoleId,
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
        ];

        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $user = UserFactory::find()->where(compact('username'))->firstOrFail();

        $this->assertActionLogsCount(1);
        $this->assertActionLogExists(['user_id' => $admin->id]);
        $this->assertEntitiesHistoryCount(1, [
            'foreign_model' => 'Users',
            'foreign_key' => $user->get('id'),
            'crud' => EntityHistory::CRUD_CREATE,
            'action_log_id' => ActionLogFactory::find()->firstOrFail()->get('id'),
        ]);

        // Assert User Action Logs
        $this->getJson("/actionlog/user/{$user->get('id')}.json");
        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertCount(1, $body);
        $creator = $body[0]['creator'];
        $data = $body[0]['data'];
        $expected = ['user' => [
            'id' => $user->get('id'),
            'role_id' => $adminRoleId,
            'username' => $username,
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
            'last_logged_in' => null,
        ]];
        $this->assertSame($expected, $data);
        $this->assertSame($admin->id, $creator['id']);
        $this->assertSame($admin->profile->first_name, $creator['profile']['first_name']);
    }
}
