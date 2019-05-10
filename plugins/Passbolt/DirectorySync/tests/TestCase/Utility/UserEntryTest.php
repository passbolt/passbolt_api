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
use Passbolt\DirectorySync\Actions\AllSyncAction;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Error\Exception\ValidationException;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry;

class UserEntryTest extends DirectorySyncIntegrationTestCase
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
        $userData = [
            'firstName' => 'john',
            'lastName' => 'doe',
            'emailAddress' => 'john.doe@passbolt.com',
            'dn' => 'CN=john,OU=accounts,OU=passbolt,OU=local',
            'guid' => UuidFactory::uuid('ldap.user.id.john'),
            'created' => new FrozenTime(),
            'modified' => new FrozenTime(),
        ];

        $userData = array_merge($userData, $modify);

        // Remove elements that should be removed.
        foreach($userData as $key => $value) {
            if ($value === null) {
                unset($userData[$key]);
            }
        }

        $ldapObject = new LdapObject($userData,LdapObjectType::USER);

        return $ldapObject;
    }

    public function testMappingSuccess()
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

    public function testValidateErrorNoId()
    {
        $ldapObject = $this->_getSampleLdapObject(['guid' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertEmpty($userEntry->id);
        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['id']);
    }

    public function testValidateErrorInvalidId()
    {
        $ldapObject = $this->_getSampleLdapObject(['guid' => 'thisisnotavalidguid']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['id']);
    }

    public function testValidateErrorNoDn()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['dn']);
    }

    public function testValidateErrorDnIsInvalid()
    {
        $ldapObject = $this->_getSampleLdapObject(['dn' => 'invaliddn']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['dn']);
    }

    public function testValidateErrorNoCreated()
    {
        $ldapObject = $this->_getSampleLdapObject(['created' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['created']);
    }

    public function testValidateErrorNoModified()
    {
        $ldapObject = $this->_getSampleLdapObject(['modified' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['modified']);
    }

    public function testValidateErrorNoFirstName()
    {
        $ldapObject = $this->_getSampleLdapObject(['firstName' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['first_name']);
    }

    public function testValidateErrorNoLastName()
    {
        $ldapObject = $this->_getSampleLdapObject(['lastName' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['last_name']);
    }

    public function testValidateErrorNoEmailAddress()
    {
        $ldapObject = $this->_getSampleLdapObject(['emailAddress' => null]);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['email']);
    }

    public function testValidateErrorInvalidEmailAddress()
    {
        $ldapObject = $this->_getSampleLdapObject(['emailAddress' => 'invalidemail']);

        $userEntry = new UserEntry();
        $userEntry->buildFromLdapObject($ldapObject, $this->mappingRules);

        $this->assertTrue($userEntry->hasErrors());
        $this->assertFalse($userEntry->validate());
        $this->assertNotEmpty($userEntry->errors()['email']);
    }
}
