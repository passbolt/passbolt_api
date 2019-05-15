<?php
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

use App\Test\TestCase\Controller\Resources\ResourcesAddControllerTest;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class ResourcesAddNotificationTest extends ResourcesAddControllerTest
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets',
        'app.Base/Favorites', 'app.Base/Permissions', 'app.Base/EmailQueue', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Avatars'
    ];

    public function testResourcesAddNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.password.create', false);

        $this->authenticateAs('ada');
        $data = $this->_getDummyPostData();
        $this->postJson("/resources.json", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testResourcesAddNotificationSuccess()
    {
        $this->setEmailNotificationSetting('send.password.create', true);

        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $data = $this->_getDummyPostData();
        $this->postJson("/resources.json", $data);
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
