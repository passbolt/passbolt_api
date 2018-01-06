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

use App\Test\TestCase\Controller\Resources\ResourcesUpdateControllerTest;
use App\Utility\UuidFactory;
use Cake\Core\Configure;

class ResourcesUpdateNotificationTest extends ResourcesUpdateControllerTest
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/resources', 'app.Base/secrets', 'app.Base/gpgkeys',
        'app.Base/favorites', 'app.Base/email_queue', 'app.Base/profiles', 'app.Base/roles',
        'app.Base/groups_users', 'app.Base/permissions', 'app.Base/avatars'
    ];

    public function testResourcesUpdateNotificationDisabled()
    {
        Configure::write('passbolt.email.send.password.update', false);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getDummyPostData($resource);

        // Post udpated data
        $this->authenticateAs('betty');
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testResourcesUpdateNotificationSuccess()
    {
        Configure::write('passbolt.email.send.password.update', true);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getDummyPostData($resource);

        // Post udpated data
        $this->authenticateAs('betty');
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('have updated the password');

        // email should be send to self as backup
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('have updated the password');
    }

    public function testResourcesUpdateNotificationSettings()
    {
        // Test the configuration flags like passbolt.show.resource.url
        $this->markTestIncomplete();
    }
}
