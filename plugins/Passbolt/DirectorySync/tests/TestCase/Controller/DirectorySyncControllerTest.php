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

use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;

class DirectorySyncControllerTest extends DirectorySyncIntegrationTestCase
{
    public $fixtures = [
       'app.Base/Users', 'app.Base/Groups', 'app.Base/Secrets', 'app.Base/Roles',
       'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Avatars',
       'app.Base/Favorites', 'app.Base/EmailQueue', 'app.Base/OrganizationSettings',
       'plugin.Passbolt/DirectorySync.Base/DirectoryEntries',
       'plugin.Passbolt/DirectorySync.Base/DirectoryIgnore',
       'plugin.Passbolt/DirectorySync.Base/DirectoryRelations',
       'plugin.Passbolt/DirectorySync.DirectoryReports',
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
