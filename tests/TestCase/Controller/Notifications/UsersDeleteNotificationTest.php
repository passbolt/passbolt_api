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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;

class UsersDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Base/GroupsUsers', 'app.Base/Permissions',
    ];

    public function testUsersDeleteNotificationSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.ursula');
        $this->deleteJson('/users/' . $francesId . '.json');
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

        // Two mails should be in the queue
        $this->assertEmailQueueCount(2);
    }
}
