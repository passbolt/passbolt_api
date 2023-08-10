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
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class UsersRegisterControllerTest extends LogIntegrationTestCase
{
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public function testUsersRegisterPostSuccess()
    {
        $this->setSelfRegistrationSettingsData();
        RoleFactory::make()->user()->persist();
        $data = [
            'username' => 'ping.fu@passbolt.com',
            'profile' => [
                'first_name' => '傅',
                'last_name' => '苹',
            ],
        ];
        $this->postJson('/users/register.json', $data);
        $this->assertResponseSuccess();

        // Check that an email was sent
        $this->assertEmailIsInQueue([
            'email' => $data['username'],
            'subject' => "Welcome to passbolt, {$data['profile']['first_name']}!",
            'template' => 'AN/user_register_self',
        ]);

        $user = UserFactory::find()->where(['username' => $data['username']])->firstOrFail();
        $this->assertActionLogsCount(1);
        $this->assertEntitiesHistoryCount(1, [
            'foreign_model' => 'Users',
            'foreign_key' => $user->get('id'),
            'crud' => EntityHistory::CRUD_CREATE,
            'action_log_id' => ActionLogFactory::find()->firstOrFail()->get('id'),
        ]);
    }
}
