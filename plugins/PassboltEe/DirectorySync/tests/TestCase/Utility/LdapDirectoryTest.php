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
 * @since         4.0.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Utility;

use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\NotImplementedException;
use LdapRecord\Query\Model\Builder;
use Passbolt\DirectorySync\Test\Mock\LdapDirectoryMock;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\LdapDirectory;

/**
 * LdapDirectoryTest class
 *
 * @package Passbolt\DirectorySync\Test\TestCase\Utility
 */
class LdapDirectoryTest extends AppTestCase
{
    public const BASE_DN = 'dc=example,dc=org';
    public const TEST_DOMAIN = 'org_domain';

    /**
     * @var DirectoryOrgSettings
     */
    public $settings;

    /**
     * @var LdapDirectoryMock
     */
    public $Mock;

    public function setUp(): void
    {
        parent::setUp();
        $this->settings = new DirectoryOrgSettings(DirectoryOrgSettingsTest::getDummySettings());
        $this->initLdap();
    }

    /**
     * Init LDAP mock with default settings
     */
    private function initLdap()
    {
        $this->Mock = LdapDirectoryMock::createDefault($this, $this->settings);
    }

    /**
     * Generate dummy directory results
     *
     * @return array
     */
    private function generateDirectoryResults(array $modifyGroups = [], array $modifyUsers = []): array
    {
        $users = [
            [
                'id' => '5eb425ec-3a77-103d-8a10-2d3e22d42cf8',
                'directory_name' => 'uid=user1,ou=Users,dc=example,dc=org',
                'user' => [
                    'username' => 'user1@example.com',
                    'profile' => [
                        'first_name' => 'User',
                        'last_name' => 'One',
                    ],
                ],
                'directory_created' => '2023-01-01 00:00',
                'directory_modified' => '2023-01-01 00:00',
                'type' => 'user',
            ],
            [
                'id' => '5eb425ec-3a77-103d-8a10-2d3e22d42cf9',
                'directory_name' => 'uid=user2,ou=Users,dc=example,dc=org',
                'user' => [
                    'username' => 'user2@example.com',
                    'profile' => [
                        'first_name' => 'User',
                        'last_name' => 'Two',
                    ],
                ],
                'directory_created' => '2023-01-01 00:00',
                'directory_modified' => '2023-01-01 00:00',
                'type' => 'user',
            ],
        ];
        $groups = [
            [
                'id' => '5eb425ec-3a77-103d-8a10-2d3e22d42ce0',
                'directory_name' => 'cn=group1,ou=Groups,dc=example,dc=org',
                'directory_created' => '2023-01-01 00:00',
                'directory_modified' => '2023-01-01 00:00',
                'group' => [
                    'name' => 'group1',
                    'members' => [],
                    'groups' => [],
                    'users' => [],
                ],
                'type' => 'group',
            ],
            [
                'id' => '5eb425ec-3a77-103d-8a10-2d3e22d42ce1',
                'directory_name' => 'cn=group2,ou=Groups,dc=example,dc=org',
                'directory_created' => '2023-01-01 00:00',
                'directory_modified' => '2023-01-01 00:00',
                'group' => [
                    'name' => 'group2',
                    'members' => [],
                    'groups' => [],
                    'users' => [],
                ],
                'type' => 'group',
            ],
        ];

        return [array_merge($groups, $modifyGroups), array_merge($users, $modifyUsers)];
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getDNFullPath()
    {
        $userDNFullPath = $this->Mock->Ldap->getDNFullPath(DirectoryInterface::ENTRY_TYPE_USER);
        $this->assertEquals(ltrim($this->settings->getObjectPath(DirectoryInterface::ENTRY_TYPE_USER) . ',' . self::BASE_DN, ','), $userDNFullPath);

        $groupDNFullPath = $this->Mock->Ldap->getDNFullPath(DirectoryInterface::ENTRY_TYPE_GROUP);
        $this->assertEquals(ltrim($this->settings->getObjectPath(DirectoryInterface::ENTRY_TYPE_GROUP) . ',' . self::BASE_DN, ','), $groupDNFullPath);
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getDirectoryTypeName()
    {
        $this->Mock->Ldap->setDirectoryType(self::TEST_DOMAIN, DirectoryInterface::TYPE_OPENLDAP);
        $this->assertEquals(DirectoryInterface::TYPE_NAME_OPENLDAP, $this->Mock->Ldap->getDirectoryTypeName());
        $this->Mock->Ldap->setDirectoryType(self::TEST_DOMAIN, DirectoryInterface::TYPE_AD);
        $this->assertEquals(DirectoryInterface::TYPE_NAME_AD, $this->Mock->Ldap->getDirectoryTypeName());
        $this->Mock->Ldap->setDirectoryType(self::TEST_DOMAIN, DirectoryInterface::TYPE_FREEIPA);
        $this->assertEquals(DirectoryInterface::TYPE_NAME_FREEIPA, $this->Mock->Ldap->getDirectoryTypeName());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_setUsers()
    {
        $this->expectException(NotImplementedException::class);
        $this->Mock->Ldap->setUsers([]);
    }

    /**
     * @return void
     */
    public function testLdapDirectory_setGroups()
    {
        $this->expectException(NotImplementedException::class);
        $this->Mock->Ldap->setGroups([]);
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getConnection()
    {
        $this->expectException(\RuntimeException::class);
        $Ldap = new LdapDirectory($this->settings);
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getMappingRules_Successful()
    {
        $this->Mock->Ldap->setDirectoryType(self::TEST_DOMAIN, DirectoryInterface::TYPE_AD);
        $this->assertEquals($this->settings->getFieldsMapping(), $this->Mock->Ldap->getMappingRules());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getMappingRules_WrongDirectoryTypeException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/The directory type should be one of the following:.*/');
        $this->Mock->Ldap->setDirectoryType(self::TEST_DOMAIN, 'invalid-ldap');
        $this->Mock->Ldap->getMappingRules();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getFilteredDirectoryResults_emptyUsersParentGroupGroupsParentGroup()
    {
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [$expectedGroups, $expectedUsers] = $this->generateDirectoryResults();
        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults, $expectedUsers);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults, $expectedGroups);
        $this->Mock->setFetchDirectoryDataExpectation($directoryResults);

        $results = $this->Mock->Ldap->getFilteredDirectoryResults();
        $this->assertEquals($expectedUsers, $results->getUsersAsArray());
        $this->assertEquals($expectedGroups, $results->getGroupsAsArray());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getFilteredDirectoryResults_emptyUsersParentGroupNotEmptyGroupsParentGroup()
    {
        $settings = $this->settings->toArray();
        $settings['groupsParentGroup'] = 'group1';
        $this->settings->set($settings);
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [$groups, $expectedUsers] = $this->generateDirectoryResults();
        $expectedGroups = [$groups[0]];

        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults, $expectedUsers);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults, $groups);

        $filteredGroups = $this->Mock->generateDirectoryResultsMock();
        $this->Mock->setDirectoryResultsUsersExpectation($filteredGroups);
        $this->Mock->setDirectoryResultsGroupsExpectation($filteredGroups, $expectedGroups);
        $this->Mock->setDirectoryResultsRecursivelyFromParentGroupExpectation($directoryResults, DirectoryInterface::ENTRY_TYPE_GROUP, $this->settings->getGroupsParentGroup(), $filteredGroups);

        $this->Mock->setFetchDirectoryDataExpectation($directoryResults);

        $results = $this->Mock->Ldap->getFilteredDirectoryResults();
        $this->assertEquals($expectedUsers, $results->getUsersAsArray());
        $this->assertEquals($expectedGroups, $results->getGroupsAsArray());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getFilteredDirectoryResults_notEmptyUsersParentGroupEmptyGroupsParentGroup()
    {
        $settings = $this->settings->toArray();
        $settings['usersParentGroup'] = 'group1';
        $this->settings->set($settings);
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [$expectedGroups, $users] = $this->generateDirectoryResults();
        $expectedUsers = [$users[0]];
        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults, $users);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults, $expectedGroups);

        $filteredUsers = $this->Mock->generateDirectoryResultsMock();
        $this->Mock->setDirectoryResultsUsersExpectation($filteredUsers, $expectedUsers);
        $this->Mock->setDirectoryResultsGroupsExpectation($filteredUsers);
        $this->Mock->setDirectoryResultsRecursivelyFromParentGroupExpectation($directoryResults, DirectoryInterface::ENTRY_TYPE_USER, $this->settings->getUsersParentGroup(), $filteredUsers);

        $this->Mock->setFetchDirectoryDataExpectation($directoryResults);
        $results = $this->Mock->Ldap->getFilteredDirectoryResults();
        $this->assertEquals($expectedUsers, $results->getUsersAsArray());
        $this->assertEquals($expectedGroups, $results->getGroupsAsArray());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getFilteredDirectoryResults_notEmptyUsersParentGroupNotEmptyGroupsParentGroup()
    {
        $settings = $this->settings->toArray();
        $settings['usersParentGroup'] = 'group1';
        $settings['groupsParentGroup'] = 'group2';
        $this->settings->set($settings);
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [$groups, $users] = $this->generateDirectoryResults();
        $expectedUsers = [$users[0]];
        $expectedGroups = [$groups[0]];
        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults, $users);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults, $groups);

        $filteredUsers = $this->Mock->generateDirectoryResultsMock();
        $filteredGroups = $this->Mock->generateDirectoryResultsMock();

        $this->Mock->setDirectoryResultsUsersExpectation($filteredUsers, $expectedUsers);
        $this->Mock->setDirectoryResultsGroupsExpectation($filteredGroups, $expectedGroups);

        $this->Mock->setDirectoryResultsRecursivelyFromParentGroupConsecutiveExpectation(
            $directoryResults,
            [
                [DirectoryInterface::ENTRY_TYPE_USER, $this->settings->getUsersParentGroup()],
                [DirectoryInterface::ENTRY_TYPE_GROUP, $this->settings->getGroupsParentGroup()],
            ],
            [$filteredUsers, $filteredGroups]
        );

        $this->Mock->setFetchDirectoryDataExpectation($directoryResults);
        $results = $this->Mock->Ldap->getFilteredDirectoryResults();
        $this->assertEquals($expectedUsers, $results->getUsersAsArray());
        $this->assertEquals($expectedGroups, $results->getGroupsAsArray());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_NoEnableConditionNoCustomQueries()
    {
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_EnableConditionNoCustomQueries()
    {
        $settings = $this->settings->toArray();
        $settings['enabledUsersOnly'] = true;
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_NoEnableConditionUserCustomQueries()
    {
        $settings = $this->settings->toArray();
        $settings['userCustomFilters'] = '(usercustom=true)';
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_NoEnableConditionGroupCustomQueries()
    {
        $settings = $this->settings->toArray();
        $settings['groupCustomFilters'] = '(groupcustom=true)';
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_NoEnableConditionUserCustomQueriesParseException()
    {
        $settings = $this->settings->toArray();
        $settings['userCustomFilters'] = '(usercustom=true';
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error has occurred parsing userCustomFilter: Unclosed filter group. Missing ")" parenthesis');
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_NoEnableConditionGroupCustomQueriesParseException()
    {
        $settings = $this->settings->toArray();
        $settings['groupCustomFilters'] = '(groupcustom=true';
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error has occurred parsing groupCustomFilter: Unclosed filter group. Missing ")" parenthesis');
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_UserCustomQueryCallback()
    {
        $settings = $this->settings->toArray();
        $settings['userCustomFilters'] = function (Builder $b) {
            return $b->where(['usercustom' => true]);
        };
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Using callbacks for userCustomFilter is not supported anymore. Please use LDAP search filter instead.');
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_GroupCustomQueryCallback()
    {
        $settings = $this->settings->toArray();
        $settings['groupCustomFilters'] = function (Builder $b) {
            return $b->where(['groupcustom' => true]);
        };
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Using callbacks for groupCustomFilter is not supported anymore. Please use LDAP search filter instead.');
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_GroupAndUserCustomQueries()
    {
        $settings = $this->settings->toArray();
        $settings['userCustomFilters'] = '(usercustom=true)';
        $settings['groupCustomFilters'] = '(groupcustom=true)';
        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_fetchDirectoryData_EnableConditionGroupAndUserCustomQueries()
    {
        $settings = $this->settings->toArray();
        $settings['enabledUsersOnly'] = true;
        $settings['userCustomFilters'] = '(usercustom=true)';
        $settings['groupCustomFilters'] = '(groupcustom=true)';

        $this->settings->set($settings);
        $this->Mock = LdapDirectoryMock::createAllowingFetch($this, $this->settings);
        $this->Mock->setGetQueryExpectation();
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        $directoryResults->expects($this->once())
            ->method('initializeWithLdapResults');
        /** @psalm-suppress InvalidArgument argument is actually a mock of the expected type */
        $this->Mock->Ldap->setDirectoryResults($directoryResults);
        $this->Mock->Ldap->fetchDirectoryData();
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getUsers()
    {
        $this->Mock = LdapDirectoryMock::createWithoutResultsProcessing($this, $this->settings);
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [, $expectedUsers] = $this->generateDirectoryResults();
        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults, $expectedUsers);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults);
        $this->Mock->setFilteredDirectoryResultsExpectation($directoryResults);
        $this->assertEquals($expectedUsers, $this->Mock->Ldap->getUsers());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getGroups()
    {
        $this->Mock = LdapDirectoryMock::createWithoutResultsProcessing($this, $this->settings);
        $directoryResults = $this->Mock->generateDirectoryResultsMock();
        [$expectedGroups,] = $this->generateDirectoryResults();
        $this->Mock->setDirectoryResultsUsersExpectation($directoryResults);
        $this->Mock->setDirectoryResultsGroupsExpectation($directoryResults, $expectedGroups);
        $this->Mock->setFilteredDirectoryResultsExpectation($directoryResults);
        $this->assertEquals($expectedGroups, $this->Mock->Ldap->getGroups());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getUserFiltersAsString()
    {
        $this->Mock->setFetchAndInitializeQueryExpectation(DirectoryInterface::ENTRY_TYPE_USER);
        $this->assertEquals(DirectoryInterface::ENTRY_TYPE_USER, $this->Mock->Ldap->getUserFiltersAsString());
    }

    /**
     * @return void
     */
    public function testLdapDirectory_getGroupFiltersAsString()
    {
        $this->Mock->setFetchAndInitializeQueryExpectation(DirectoryInterface::ENTRY_TYPE_GROUP);
        $this->assertEquals(DirectoryInterface::ENTRY_TYPE_GROUP, $this->Mock->Ldap->getGroupFiltersAsString());
    }
}
