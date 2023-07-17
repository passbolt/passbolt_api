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
use Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry;

class UserEntryTest extends DirectorySyncIntegrationTestCase
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
        $userData = [
            'givenName' => 'john',
            'sn' => 'doe',
            'mail' => 'john.doe@passbolt.com',
            'dn' => 'CN=john,OU=accounts,OU=passbolt,OU=local',
            'objectGuid' => UuidFactory::uuid('ldap.user.id.john'),
            'whenCreated' => new \DateTime(),
            'whenChanged' => new \DateTime(),
        ];

        $userData = array_merge($userData, $modify);

        // Remove elements that should be removed.
        foreach ($userData as $key => $value) {
            if ($value === null) {
                unset($userData[$key]);
            }
        }

        return $this->getTestLdapUserObject($userData);
    }

    public function testDirectoryMappingSuccess()
    {
        $ldapObject = $this->_getSampleLdapObject();

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertFalse($userEntry->hasErrors());
        $this->assertEquals($userEntry->user['username'], 'john.doe@passbolt.com');
        $this->assertEquals($userEntry->user['profile']['first_name'], 'john');
        $this->assertEquals($userEntry->user['profile']['last_name'], 'doe');
        $this->assertEquals($userEntry->dn, 'CN=john,OU=accounts,OU=passbolt,OU=local');
        $this->assertEquals($userEntry->id, UuidFactory::uuid('ldap.user.id.john'));
        $this->assertTrue($userEntry->validate());
    }

    public function testDirectoryValidateErrorNoId()
    {
        $ldapObject = $this->_getSampleLdapObject(['objectGuid' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertEmpty($userEntry->id);
        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['id']);
    }

    public function testDirectoryValidateErrorInvalidId()
    {
        $ldapObject = $this->_getSampleLdapObject(['objectGuid' => 'thisisnotavalidguid']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['id']);
    }

    public function testDirectoryValidateErrorNoDn()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['dn']);
    }

    public function testDirectoryValidateErrorDnIsInvalid()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => 'invaliddn']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['dn']);
    }

    public function testDirectoryValidateErrorNoCreated()
    {
        $ldapObject = $this->_getSampleLdapObject(['whenCreated' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['created']);
    }

    public function testDirectoryValidateErrorNoModified()
    {
        $ldapObject = $this->_getSampleLdapObject(['whenChanged' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['modified']);
    }

    public function testDirectoryValidateErrorNoFirstName()
    {
        $ldapObject = $this->_getSampleLdapObject(['givenName' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['first_name']);
    }

    public function testDirectoryValidateErrorNoLastName()
    {
        $ldapObject = $this->_getSampleLdapObject(['sn' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['last_name']);
    }

    public function testDirectoryValidateErrorNoEmailAddress()
    {
        $ldapObject = $this->_getSampleLdapObject(['mail' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['email']);
    }

    public function testDirectoryValidateErrorInvalidEmailAddress()
    {
        $ldapObject = $this->_getSampleLdapObject(['mail' => 'invalidemail']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['email']);
    }
}
