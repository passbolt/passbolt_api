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

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UsersDeleteNotificationTest extends AppIntegrationTestCase
{
    public $Users;
    public $GroupsUsers;
    public $Permissions;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles',
        'app.Base/resources', 'app.Base/email_queue', 'app.Base/favorites',
        'app.Base/groups_users', 'app.Base/permissions', 'app.Base/avatars'
    ];

    public function testUsersDeleteNotificationSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.ursula');
        $this->deleteJson('/users/' . $francesId . '.json?api-version=v1');
        $this->assertSuccess();

        $this->get('/seleniumtests/showlastemail/ping@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('deleted the user Ursula');
        $this->assertResponseContains('Human resource');
        $this->assertResponseContains('IT support');

        $this->get('/seleniumtests/showlastemail/thelma@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('deleted the user Ursula');
        $this->assertResponseContains('Human resource');
        $this->assertResponseNotContains('IT support');

        $this->get('/seleniumtests/showlastemail/wang@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }
}
