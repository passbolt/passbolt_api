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

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Routing\Router;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class UsersRecoverControllerTest extends LogIntegrationTestCase
{
    use EmailQueueTrait;

    public function testUsersRecoverController_Post_Success_Active_User()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $username = $user->username;
        $this->mockUserAgent();
        $this->postJson('/users/recover.json', compact('username'));
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertEmailIsInQueue(['email' => $username, 'template' => 'AN/user_recover']);
        $this->assertEmailInBatchContains('You just requested to recover your passbolt account on this device.', $username);
        $this->assertEmailInBatchContains('/setup/recover/start/' . $user->id . '/' . $token->get('token') . '?case=default', $username);
        $this->assertEmailQueueCount(1);
        $this->assertActionLogsCount(1);
        $this->assertEntitiesHistoryCount(0);
    }

    public function testUsersRecoverController_Post_Success_User_Has_Not_Completed_Setup()
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $username = $user->username;
        $this->mockUserAgent();
        $this->postJson('/users/recover.json', compact('username'));
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $this->assertEmailIsInQueue(['email' => $username, 'template' => 'AN/user_register_self']);
        $this->assertEmailQueueCount(1);
        $link = '<a href="' . Router::url('/', true) . '">' . Router::url('/', true) . '</a>';
        $this->assertEmailInBatchContains("You just opened an account on passbolt at {$link}.", $username);
        $this->assertActionLogsCount(1);
        $this->assertEntitiesHistoryCount(0);
    }
}
