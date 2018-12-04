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
 * @since         2.2.0
 */

namespace App\Test\TestCase\Controller\Favorites;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;

class DirectoryIgnoreDeleteControllerTest extends DirectorySyncIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets', 'app.Base/roles',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue', 'app.Base/organization_settings',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
    ];

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteSuccess()
    {
        $this->authenticateAs('admin');
        $recordId = UuidFactory::uuid('user.id.ada');
        $this->postJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->deleteJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->assertSuccess();
        $DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
        $deletedIgnore = $DirectoryIgnore->find('all')->where(['id' => $recordId])->first();
        $this->assertempty($deletedIgnore);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteErrorNotValidId()
    {
        $this->authenticateAs('admin');
        $recordId = 'invalid-id';
        $this->deleteJson("/directorysync/ignore/users/$recordId.json");
        $this->assertError(400);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteErrorNotExist()
    {
        $this->authenticateAs('admin');
        $recordId = UuidFactory::uuid();
        $this->deleteJson("/directorysync/ignore/users/$recordId.json");
        $this->assertError(404);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteNotExistAlreadyDeleted()
    {
        $this->authenticateAs('admin');
        $recordId = UuidFactory::uuid('user.id.ada');
        $this->postJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->deleteJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->deleteJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->assertError(404);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteErrorWrongModel()
    {
        $this->authenticateAs('admin');
        $recordId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson("/directorysync/ignore/biloute/$recordId.json");
        $this->assertError(400);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteErrorNotExistModel()
    {
        $this->authenticateAs('admin');
        $recordId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson("/directorysync/ignore/groups/$recordId.json");
        $this->assertError(404);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncControllerIgnore
     * @group DirectorySyncControllerIgnoreDelete
     */
    public function testDirectorySyncControllerIgnoreDeleteErrorNotAuthenticated()
    {
        $recordId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson("/directorysync/ignore/users/$recordId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
