<?php
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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Core\Configure;

class GroupsAddNotificationTest extends AppIntegrationTestCase
{
    public $Groups;

    public $fixtures = [
        'app.Base/groups', 'app.Base/users', 'app.Base/groups_users', 'app.Base/profiles', 'app.Base/roles',
        'app.Base/email_queue', 'app.Base/avatars'
    ];

    public function testGroupsUsersAddNotificationDisabled()
    {
        Configure::write('passbolt.email.send.group.user.add', false);
        $this->authenticateAs('admin');
        $this->postJson('/groups.json?api-version=v1', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]]
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testGroupsUsersAddNotificationSuccess()
    {
        Configure::write('passbolt.email.send.group.user.add', true);
        $this->authenticateAs('admin');
        $this->postJson('/groups.json?api-version=v1', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => true]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.admin')]]
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('added you to the group Temp Group');
        $this->assertResponseContains('And as group manager you');
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseNotContains('And as group manager you');
        $this->assertResponseCode(200);

        // emails are not send if you add yourself to a group
        $this->get('/seleniumtests/showLastEmail/admin@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }
}
