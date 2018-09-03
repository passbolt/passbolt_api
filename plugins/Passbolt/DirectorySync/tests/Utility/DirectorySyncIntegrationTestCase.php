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
namespace Passbolt\DirectorySync\Test\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertReportTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;

abstract class DirectorySyncIntegrationTestCase extends AppIntegrationTestCase
{
    use AssertDirectoryTrait;
    use AssertReportTrait;
    use MockDirectoryTrait;

    public $fixtures = [
        'app.Base/users',
        'app.Base/profiles',
        'app.Base/groups',
        'app.Base/authentication_tokens',
        'app.Base/secrets',
        'app.Base/roles',
        'app.Base/resources',
        'app.Alt0/groups_users',
        'app.Alt0/permissions',
        'app.Base/avatars',
        'app.Base/favorites',
        'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
        'plugin.passbolt/directorySync.base/directoryRelations',
        'plugin.passbolt/directorySync.directoryReports',
        'plugin.passbolt/directorySync.directoryReportsItems',
    ];

    /**
     * @var \Passbolt\DirectorySync\Utility\SyncAction
     */
    protected $action;

    public function setUp()
    {
        parent::setUp();
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $this->Groups = TableRegistry::get('Groups');
        $this->Users = TableRegistry::get('Users');
        $this->DirectoryEntries = TableRegistry::get('DirectoryEntries');
        Configure::write('passbolt.plugins.directorySync.test', true);
        Configure::write('passbolt.plugins.directorySync.defaultUser', 'admin@passbolt.com');
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', 'ada@passbolt.com');
    }
}
