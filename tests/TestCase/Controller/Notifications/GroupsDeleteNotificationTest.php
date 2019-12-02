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

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public $Groups;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/Users', 'app.Base/Resources', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/EmailQueue',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Avatars', 'app.Base/Gpgkeys', 'app.Base/Secrets',
    ];

    public function testGroupsDeleteNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.group.delete', false);

        $this->authenticateAs('edith');
        $this->deleteJson('/groups/' . UuidFactory::uuid('group.id.freelancer') . '.json');
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/edith@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
        $this->get('/seleniumtests/showLastEmail/frances@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testGroupsDeleteNotificationSuccess()
    {
        $this->setEmailNotificationSetting('send.group.delete', true);

        $this->authenticateAs('edith');
        $this->deleteJson('/groups/' . UuidFactory::uuid('group.id.freelancer') . '.json');
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/frances@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('deleted the group ');

        // emails are not send if you add yourself to a group
        $this->get('/seleniumtests/showLastEmail/edith@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }
}
