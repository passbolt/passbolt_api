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

use App\Test\TestCase\Controller\Resources\ResourcesUpdateControllerTest;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class ResourcesUpdateNotificationTest extends ResourcesUpdateControllerTest
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Gpgkeys',
        'app.Base/Favorites', 'app.Base/EmailQueue', 'app.Base/Profiles', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Permissions', 'app.Base/Avatars', 'app.Base/OrganizationSettings',
    ];

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    public function setUp()
    {
        parent::setUp();
        $this->loadNotificationSettings();
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown()
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testResourcesUpdateNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.password.update', false);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];

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
        $this->setEmailNotificationSetting('send.password.update', true);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];

        // Post udpated data
        $this->authenticateAs('betty');
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('updated the password');

        // email should be send to self as backup
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('updated the password');
    }

    public function testResourcesUpdateNotificationSettings()
    {
        // Test the configuration flags like passbolt.show.resource.url
        $this->markTestIncomplete();
    }
}
