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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectCollection;
use LdapTools\Object\LdapObjectType;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectoryResultsObjectTransformationTest extends DirectorySyncIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Assert that entry ids are generated on the fly if not provided by the directory.
     * @throws \Exception
     */
    public function testUserEntryIdProvidedIsNotTransformed() {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync']);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping.ad');

        // userData without guid.
        $userData = [
            'guid' => UuidFactory::uuid('ldap.user.id.john'),
            'firstName' => 'john',
            'lastName' => 'doe',
            'emailAddress' => 'john.doe@passbolt.com',
            'dn' => 'CN=john,OU=accounts,OU=passbolt,OU=local',
            'created' => new FrozenTime(),
            'modified' => new FrozenTime(),
        ];
        $ldapObject = new LdapObject($userData,LdapObjectType::USER);
        $ldapUsers = new LdapObjectCollection();
        $ldapUsers->add($ldapObject);
        $ldapGroups = new LdapObjectCollection();

        $DirectoryResults = new DirectoryResults($mappingRules);
        $DirectoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        $users = $DirectoryResults->getUsers();
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']));
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id));
        $this->assertNotEmpty(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id));
        $this->assertEquals($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id, UuidFactory::uuid('ldap.user.id.john'));
    }

    /**
     * Assert that entry ids are generated on the fly if not provided by the directory.
     * @throws \Exception
     */
    public function testUserEntryTransformNoId() {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync']);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping.ad');

        // userData without guid.
        $userData = [
            'firstName' => 'john',
            'lastName' => 'doe',
            'emailAddress' => 'john.doe@passbolt.com',
            'dn' => 'CN=john,OU=accounts,OU=passbolt,OU=local',
            'created' => new FrozenTime(),
            'modified' => new FrozenTime(),
        ];
        $ldapObject = new LdapObject($userData,LdapObjectType::USER);
        $ldapUsers = new LdapObjectCollection();
        $ldapUsers->add($ldapObject);
        $ldapGroups = new LdapObjectCollection();

        $DirectoryResults = new DirectoryResults($mappingRules);
        $DirectoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        $users = $DirectoryResults->getUsers();
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']));
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id));
        $this->assertNotEmpty(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id));
        $this->assertEquals($users['CN=john,OU=accounts,OU=passbolt,OU=local']->id, UuidFactory::uuid('CN=john,OU=accounts,OU=passbolt,OU=local'));
    }

    /**
     * Assert that emails are transformed on the fly if the configuration dictates it.
     * @throws \Exception
     */
    public function testUserEntryTransformEmail() {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync']);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping.ad');

        // userData with no email, but a uid
        $userData = [
            'guid' => UuidFactory::uuid('ldap.user.id.john'),
            'firstName' => 'john',
            'lastName' => 'doe',
            'uid' => 'jdoe',
            'dn' => 'CN=john,OU=accounts,OU=passbolt,OU=local',
            'created' => new FrozenTime(),
            'modified' => new FrozenTime(),
        ];
        $ldapObject = new LdapObject($userData,LdapObjectType::USER);
        $ldapUsers = new LdapObjectCollection();
        $ldapUsers->add($ldapObject);
        $ldapGroups = new LdapObjectCollection();

        // Save corresponding settings.
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = [
            'useEmailPrefixSuffix' => true,
            'emailPrefix' => 'uid',
            'emailSuffix' => '@passbolt.com',
        ];
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        // Check directory results with email transformation.
        $DirectoryResults = new DirectoryResults($mappingRules);
        $DirectoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        $users = $DirectoryResults->getUsers();
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']));
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->user));
        $this->assertTrue(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->user['username']));
        $this->assertNotEmpty(isset($users['CN=john,OU=accounts,OU=passbolt,OU=local']->user['username']));
        $this->assertEquals($users['CN=john,OU=accounts,OU=passbolt,OU=local']->user['username'], 'jdoe@passbolt.com');
    }
}