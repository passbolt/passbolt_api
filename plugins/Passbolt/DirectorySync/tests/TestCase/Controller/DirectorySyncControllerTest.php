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
 * @since         2.6.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Controller;

use App\Model\Entity\Role;

use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Test\TestCase\Form\LdapConfigurationFormTest;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySyncControllerTest extends DirectorySyncIntegrationTestCase
{
    public $fixtures = [
       'app.Base/users', 'app.Base/groups', 'app.Base/secrets', 'app.Base/roles',
       'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
       'app.Base/favorites', 'app.Base/email_queue', 'app.Base/organization_settings',
       'plugin.passbolt/directorySync.base/directoryEntries',
       'plugin.passbolt/directorySync.base/directoryIgnore',
       'plugin.passbolt/directorySync.base/directoryRelations',
       'plugin.passbolt/directorySync.directoryReports',
    ];

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncAsNonAdmin()
    {
        $this->authenticateAs('ada');
        $this->getJson("/directorysync/synchronize.json?api-version=2");
        $this->assertResponseError('Only administrators can access directory sync functionalities');
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncAsAdmin()
    {
        $this->authenticateAs('admin');
        $this->getJson("/directorysync/synchronize.json?api-version=2");
        $this->assertSuccess();
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncSimulateAsNonAdmin()
    {
        $this->authenticateAs('ada');
        $this->getJson("/directorysync/synchronize/dry-run.json?api-version=2");
        $this->assertResponseError('Only administrators can access directory sync functionalities');
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncSimulateAsAdmin()
    {
        $this->authenticateAs('admin');
        $this->getJson("/directorysync/synchronize/dry-run.json?api-version=2");
        $this->assertSuccess();
    }
}
