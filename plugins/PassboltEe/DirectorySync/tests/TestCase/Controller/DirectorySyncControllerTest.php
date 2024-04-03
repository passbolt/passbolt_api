<?php
declare(strict_types=1);

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

/**
 * @uses \Passbolt\DirectorySync\Controller\DirectorySyncController
 */
class DirectorySyncControllerTest extends DirectorySyncIntegrationTestCase
{
    public $fixtures = [];

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncControllerAsNonAdmin()
    {
        $this->logInAsUser();
        $this->postJson('/directorysync/synchronize.json?api-version=2');
        $this->assertResponseError('Only administrators can access directory sync functionalities');
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncControllerSimulateAsNonAdmin()
    {
        $this->logInAsUser();
        $this->getJson('/directorysync/synchronize/dry-run.json?api-version=2');
        $this->assertResponseError('Only administrators can access directory sync functionalities');
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncControllerSimulateAsAdmin()
    {
        $this->logInAsAdmin();
        $this->getJson('/directorysync/synchronize/dry-run.json?api-version=2');
        $this->assertSuccess();
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testDirectorySyncControllerAsAdmin()
    {
        $this->logInAsAdmin();
        $this->postJson('/directorysync/synchronize.json?api-version=2');
        $this->assertSuccess();
    }
}
