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

use App\Model\Entity\Role;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class UsersEditControllerTest extends LogIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersEditAdminRoleEditSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $admin = $this->logInAsAdmin();
        $this->logInAs($admin);
        $data = [
            'id' => $user->id,
            'role_id' => $admin->role_id,
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->role->name, Role::ADMIN);

        $this->assertActionLogsCount(1);
        $this->assertEntitiesHistoryCount(1, [
            'foreign_model' => 'Users',
            'foreign_key' => $user->id,
            'crud' => EntityHistory::CRUD_UPDATE,
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
            'role_id' => $admin->role_id, // The user is now admin!
            'username' => $user->username,
            'profile' => [
                'first_name' => $user->profile->first_name,
                'last_name' => $user->profile->last_name,
            ],
            'last_logged_in' => null,
        ]];
        $this->assertSame($expected, $data);
        $this->assertSame($admin->id, $creator['id']);
        $this->assertSame($admin->profile->first_name, $creator['profile']['first_name']);
    }
}
