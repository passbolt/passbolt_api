<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.0
 */

namespace Passbolt\AuditLog\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * @uses \Passbolt\AuditLog\Controller\UserLogsController
 */
class UserLogsControllerTest extends LogIntegrationTestCase
{
    public function testUserLogsController_Not_Admin_Should_Not_Be_Authorized()
    {
        $this->logInAsUser();
        $this->getJson('/actionlog/user/foo.json');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Only administrators can view user logs.');
    }

    public function testUserLogsController_User_Id_Not_UUID()
    {
        $this->logInAsAdmin();
        $this->getJson('/actionlog/user/foo.json');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user identifier should be a valid UUID.');
    }

    public function testUserLogsController_User_Does_Not_Exist()
    {
        $id = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->getJson('/actionlog/user/' . $id . '.json');
        $this->assertResponseCode(404);
        $this->assertResponseContains('The user does not exist.');
    }

    public function testUserLogsController_User_Exists()
    {
        // Create a set of random entity histories
        EntitiesHistoryFactory::make(10)->persist();
        // Create a set of random user related entity histories
        EntitiesHistoryFactory::make(10)->users()
            ->with('ActionLogs.Users')
            ->with('Users')
            ->persist();

        // Create the entity histories that the finder should retrieve
        $user = UserFactory::make()->persist();
        EntitiesHistoryFactory::make(5)->users()
            ->with('ActionLogs', ActionLogFactory::make()->with('Users')->error())
            ->with('Users', $user)
            ->persist();

        EntitiesHistoryFactory::make(5)
            ->users()
            ->create()
            ->with('ActionLogs', ActionLogFactory::make()->with('Actions')->with('Users')->success())
            ->with('Users', $user)
            ->persist();

        $this->logInAsAdmin();
        $this->getJson('/actionlog/user/' . $user->id . '.json');
        $this->assertResponseOk();

        $actionLogs = $this->getResponseBodyAsArray();
        $this->assertSame(5, count($actionLogs));
        $sortedActionLogs = Hash::sort($actionLogs, '{n}.created', 'desc');
        $this->assertSame($actionLogs, $sortedActionLogs);

        foreach ($actionLogs as $actionLog) {
            $expectedData = ['user' => [
                'id' => $user->get('id'),
                'role_id' => $user->role_id,
                'username' => $user->username,
                'profile' => [
                    'first_name' => $user->profile->first_name,
                    'last_name' => $user->profile->last_name,
                ],
                'last_logged_in' => null,
            ]];
            $this->assertSame($expectedData, $actionLog['data']);
            $this->assertNotNull($actionLog['creator']);
        }
    }
}
