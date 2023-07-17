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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Utility;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry;

class GroupEntryTest extends DirectorySyncIntegrationTestCase
{
    public $mappingRules;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync' => []]);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $this->mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping.ad');
    }

    private function _getSampleLdapObject(array $modify = [])
    {
        $groupData = [
            'cn' => 'grouptest',
            'dn' => 'CN=john,OU=posixGroups,OU=passbolt,OU=local',
            'objectGuid' => UuidFactory::uuid('ldap.group.id.john'),
            'whenCreated' => new \DateTime(),
            'whenChanged' => new \DateTime(),
        ];

        $groupData = array_merge($groupData, $modify);

        // Remove elements that should be removed.
        foreach ($groupData as $key => $value) {
            if ($value === null) {
                unset($groupData[$key]);
            }
        }

        return $this->getTestLdapGroupObject($groupData);
    }

    public function testDirectoryMappingSuccess()
    {
        $ldapObject = $this->_getSampleLdapObject();

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertFalse($groupEntry->hasErrors());
        $this->assertEquals($groupEntry->group['name'], 'grouptest');
        $this->assertEquals($groupEntry->dn, 'CN=john,OU=posixGroups,OU=passbolt,OU=local');
        $this->assertEquals($groupEntry->id, UuidFactory::uuid('ldap.group.id.john'));
        $this->assertTrue($groupEntry->validate());
    }

    public function testDirectoryValidateErrorNoId()
    {
        $ldapObject = $this->_getSampleLdapObject(['objectGuid' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertEmpty($groupEntry->id);
        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['id']);
    }

    public function testDirectoryValidateErrorInvalidId()
    {
        $ldapObject = $this->_getSampleLdapObject(['objectGuid' => 'thisisnotavalidguid']);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['id']);
    }

    public function testDirectoryValidateErrorNoDn()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['dn']);
    }

    public function testDirectoryValidateErrorDnIsInvalid()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => 'invaliddn']);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['dn']);
    }

    public function testDirectoryValidateErrorNoCreated()
    {
        $ldapObject = $this->_getSampleLdapObject(['whenCreated' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['created']);
    }

    public function testDirectoryValidateErrorNoModified()
    {
        $ldapObject = $this->_getSampleLdapObject(['whenChanged' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['modified']);
    }

    public function testDirectoryValidateErrorNoName()
    {
        $ldapObject = $this->_getSampleLdapObject(['cn' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['name']);
    }
}
