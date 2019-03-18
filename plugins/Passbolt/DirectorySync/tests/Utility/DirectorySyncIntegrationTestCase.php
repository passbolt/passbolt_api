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

use App\Model\Entity\Role;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertReportTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\DirectoryOrgSettingsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

abstract class DirectorySyncIntegrationTestCase extends AppIntegrationTestCase
{
    use AssertDirectoryTrait;
    use AssertReportTrait;
    use DirectoryOrgSettingsTrait;
    use MockDirectoryTrait;
    use UserAccessControlTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Groups',
        'app.Base/AuthenticationTokens',
        'app.Base/Secrets',
        'app.Base/Roles',
        'app.Base/Resources',
        'app.Alt0/GroupsUsers',
        'app.Alt0/Permissions',
        'app.Base/Avatars',
        'app.Base/Favorites',
        'app.Base/EmailQueue',
        'app.Base/OrganizationSettings',
        'plugin.Passbolt/DirectorySync.Base/DirectoryEntries',
        'plugin.Passbolt/DirectorySync.Base/DirectoryIgnore',
        'plugin.Passbolt/DirectorySync.Base/DirectoryRelations',
        'plugin.Passbolt/DirectorySync.DirectoryReports',
        'plugin.Passbolt/DirectorySync.DirectoryReportsItems',
    ];

    public $Groups;
    public $Users;
    public $DirectoryEntries;
    public $directoryOrgSettings;

    /**
     * @var \Passbolt\DirectorySync\Utility\SyncAction
     */
    protected $action;

    public function setUp()
    {
        parent::setUp();
//        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('DirectoryEntries');
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        Configure::write('passbolt.plugins.directorySync.test', true);
        $this->enableDirectoryIntegration();
    }
}
