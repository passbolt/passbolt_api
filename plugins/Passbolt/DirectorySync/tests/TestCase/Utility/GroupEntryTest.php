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
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;
use Passbolt\DirectorySync\Error\Exception\ValidationException;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry;

class GroupEntryTest extends DirectorySyncIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync']);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $this->mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping.ad');
    }

    private function _getSampleLdapObject(array $modify = [])
    {
        $groupData = [
            'name' => 'grouptest',
            'dn' => 'CN=john,OU=posixGroups,OU=passbolt,OU=local',
            'guid' => UuidFactory::uuid('ldap.group.id.john'),
            'created' => new FrozenTime(),
            'modified' => new FrozenTime(),
        ];

        $groupData = array_merge($groupData, $modify);

        // Remove elements that should be removed.
        foreach ($groupData as $key => $value) {
            if ($value === null) {
                unset($groupData[$key]);
            }
        }

        $ldapObject = new LdapObject($groupData, LdapObjectType::GROUP);

        return $ldapObject;
    }

    public function testMappingSuccess()
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

    public function testValidateErrorNoId()
    {
        $ldapObject = $this->_getSampleLdapObject(['guid' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertEmpty($groupEntry->id);
        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['id']);
    }

    public function testValidateErrorInvalidId()
    {
        $ldapObject = $this->_getSampleLdapObject(['guid' => 'thisisnotavalidguid']);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['id']);
    }

    public function testValidateErrorNoDn()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['dn']);
    }

    public function testValidateErrorDnIsInvalid()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => 'invaliddn']);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['dn']);
    }

    public function testValidateErrorNoCreated()
    {
        $ldapObject = $this->_getSampleLdapObject(['created' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['created']);
    }

    public function testValidateErrorNoModified()
    {
        $ldapObject = $this->_getSampleLdapObject(['modified' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['modified']);
    }

    public function testValidateErrorNoName()
    {
        $ldapObject = $this->_getSampleLdapObject(['name' => null]);

        $groupEntry = new GroupEntry();
        $groupEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($groupEntry->hasErrors());
        $this->assertFalse($groupEntry->validate());
        $this->assertNotEmpty($groupEntry->errors()['name']);
    }
}
