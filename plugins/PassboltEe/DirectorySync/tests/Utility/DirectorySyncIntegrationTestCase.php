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
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Test\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertReportTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\DirectoryOrgSettingsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;
use Passbolt\DirectorySync\Utility\DirectoryInterface;

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
        'app.Base/Roles',
        'app.Base/Resources',
        'app.Alt0/GroupsUsers',
        'app.Alt0/Permissions',
        'app.Base/Favorites',
        'app.Base/Gpgkeys',
    ];

    public $Groups;
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;
    /**
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable
     */
    public $DirectoryEntries;
    /**
     * @var \Passbolt\DirectorySync\Utility\DirectoryOrgSettings
     */
    public $directoryOrgSettings;

    public function setUp(): void
    {
        parent::setUp();
        if (file_exists(CONFIG . 'ldap.php')) {
            throw new \Exception('The directory_sync tests should not run with the ldap.php configuration file enabled');
        }
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('DirectoryEntries');
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        Configure::write('passbolt.plugins.directorySync.test', true);
        $this->enableDirectoryIntegration();
        $this->loadPlugins(['Passbolt/EmailDigest' => []]);
        $this->loadPlugins(['Passbolt/Locale' => []]);
    }

    /**
     * Creates an LDAP User object with test data
     *
     * @param array $data
     * @return \LdapRecord\Models\ActiveDirectory\User
     */
    protected function getTestLdapUserObject(array $data): User
    {
        $ldapObject = new User($data);
        $ldapObject->addAttributeValue('objectType', DirectoryInterface::ENTRY_TYPE_USER);

        return $ldapObject;
    }

    /**
     * Creates an LDAP Group object with test data
     *
     * @param array $data
     * @return \LdapRecord\Models\ActiveDirectory\Group
     */
    protected function getTestLdapGroupObject(array $data): Group
    {
        $ldapObject = new Group($data);
        $ldapObject->addAttributeValue('objectType', DirectoryInterface::ENTRY_TYPE_GROUP);

        return $ldapObject;
    }
}
