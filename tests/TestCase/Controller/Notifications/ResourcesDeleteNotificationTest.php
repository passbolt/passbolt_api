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

class ResourcesDeleteNotificationTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.users', 'app.groups', 'app.resources', 'app.secrets',
        'app.favorites', 'app.email_queue', 'app.profiles', 'app.roles',
        'app.alt0/groups_users', 'app.alt0/permissions', 'app.avatars'
    ];

    public function testResourcesDeleteNotificationDisabled()
    {
        Configure::write('passbolt.email.send.password.delete', false);
        $this->authenticateAs('ada');
        $this->deleteJson('/resources/' . UuidFactory::uuid('resource.id.april') . '.json');
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testResourcesDeleteNotificationSuccess()
    {
        Configure::write('passbolt.email.send.password.delete', true);
        $this->authenticateAs('ada');
        $this->deleteJson('/resources/' . UuidFactory::uuid('resource.id.april') . '.json');
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('deleted the password april');
    }
}
