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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use LdapRecord\Models\Collection;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectoryResultsObjectTransformationTest extends DirectorySyncIntegrationTestCase
{
    /**
     * Assert that entry ids are generated on the fly if not provided by the directory.
     *
     * @throws \Exception
     */
    public function testDirectoryUserEntryIdProvidedIsNotTransformed()
    {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync' => []]);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping');

        // userData without guid.
        $dn = 'CN=john,OU=accounts,OU=passbolt,OU=local';
        $userData = [
            'objectGuid' => UuidFactory::uuid('ldap.user.id.john'),
            'givenName' => 'john',
            'sn' => 'doe',
            'mail' => 'john.doe@passbolt.com',
            'dn' => $dn,
            'directoryType' => DirectoryInterface::TYPE_AD,
            'whenCreated' => new \DateTime(),
            'whenChanged' => new \DateTime(),
        ];
        $ldapObject = $this->getTestLdapUserObject($userData);
        $ldapUsers = new Collection();
        /** @psalm-suppress InvalidArgument see signature */
        $ldapUsers->add($ldapObject);
        $ldapGroups = new Collection();

        $DirectoryResults = new DirectoryResults($mappingRules);
        $DirectoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        $users = $DirectoryResults->getUsers();
        $expectedDn = strtolower($dn);
        $this->assertTrue(isset($users[$expectedDn]));
        $expectedUserEntry = $users[$expectedDn];
        $this->assertTrue(isset($expectedUserEntry->id));
        $this->assertNotEmpty(isset($expectedUserEntry->id));
        $this->assertEquals($expectedUserEntry->id, UuidFactory::uuid('ldap.user.id.john'));
    }

    /**
     * Assert that entry ids are generated on the fly if not provided by the directory.
     *
     * @throws \Exception
     */
    public function testDirectoryUserEntryTransformNoId()
    {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync' => []]);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping');
        // userData without guid.
        $dn = 'CN=john,OU=accounts,OU=passbolt,OU=local';
        $userData = [
            'givenName' => 'john',
            'sn' => 'doe',
            'mail' => 'john.doe@passbolt.com',
            'dn' => $dn,
            'directoryType' => DirectoryInterface::TYPE_AD,
            'whenCreated' => new \DateTime(),
            'whenChanged' => new \DateTime(),
        ];
        $ldapObject = $this->getTestLdapUserObject($userData);
        $ldapUsers = new Collection();
        /** @psalm-suppress InvalidArgument see signature */
        $ldapUsers->add($ldapObject);
        $ldapGroups = new Collection();

        $DirectoryResults = new DirectoryResults($mappingRules);
        $DirectoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        $users = $DirectoryResults->getUsers();
        $expectedDn = strtolower($dn);
        $this->assertTrue(isset($users[$expectedDn]));
        $userEntry = $users[$expectedDn];
        $this->assertTrue(isset($userEntry->id));
        $this->assertNotEmpty(isset($userEntry->id));
        $this->assertEquals($userEntry->id, UuidFactory::uuid('CN=john,OU=accounts,OU=passbolt,OU=local'));
    }

    /**
     * Assert that emails are transformed on the fly if the configuration dictates it.
     *
     * @throws \Exception
     */
    public function testDirectoryUserEntryTransformEmail()
    {
        Configure::write('passbolt.plugins.directorySync.enabled', true);
        $this->loadPlugins(['Passbolt/DirectorySync' => []]);
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        $mappingRules = Configure::read('passbolt.plugins.directorySync.fieldsMapping');

        // userData with no email, but a uid
        $dn = 'CN=john,OU=accounts,OU=passbolt,OU=local';
        $userData = [
            'objectGuid' => UuidFactory::uuid('ldap.user.id.john'),
            'givenName' => 'john',
            'sn' => 'doe',
            'uid' => 'jdoe',
            'dn' => $dn,
            'directoryType' => DirectoryInterface::TYPE_AD,
            'whenCreated' => new \DateTime(),
            'whenChanged' => new \DateTime(),
        ];
        $ldapObject = $this->getTestLdapUserObject($userData);
        $ldapUsers = new Collection();
        /** @psalm-suppress InvalidArgument see signature */
        $ldapUsers->add($ldapObject);
        $ldapGroups = new Collection();

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
        $expectedDn = strtolower($dn);
        $this->assertTrue(isset($users[$expectedDn]));
        $userEntry = $users[$expectedDn];
        $this->assertTrue(isset($userEntry->user));
        $this->assertTrue(isset($userEntry->user['username']));
        $this->assertNotEmpty(isset($userEntry->user['username']));
        $this->assertEquals($userEntry->user['username'], 'jdoe@passbolt.com');
    }
}
