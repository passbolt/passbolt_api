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

use App\Test\TestCase\Controller\Resources\ResourcesAddControllerTest;
use App\Utility\UuidFactory;
use Cake\Core\Configure;

class ResourcesAddNotificationTest extends ResourcesAddControllerTest
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/secrets',
        'app.Base/favorites', 'app.Base/permissions', 'app.Base/email_queue', 'app.Base/profiles', 'app.Base/roles', 'app.Base/avatars'
    ];

    public function testResourcesAddNotificationDisabled()
    {
        Configure::write('passbolt.email.send.password.create', false);
        $this->authenticateAs('ada');
        $data = $this->_getDummyPostData();
        $this->postJson("/resources.json?api-version=v1", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testResourcesAddNotificationSuccess()
    {
        Configure::write('passbolt.email.send.password.create', true);
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $data = $this->_getDummyPostData();
        $this->postJson("/resources.json?api-version=v1", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('You have saved a new password');
    }

    public function testResourcesAddNotificationSettings()
    {
        // Test the configuration flags like passbolt.show.resource.url
        $this->markTestIncomplete();
    }
}
