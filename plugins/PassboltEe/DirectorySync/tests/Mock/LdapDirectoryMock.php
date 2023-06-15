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
namespace Passbolt\DirectorySync\Test\Mock;

use LdapRecord\Configuration\DomainConfiguration;
use LdapRecord\Connection;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use LdapRecord\Query\Filter\Parser;
use LdapRecord\Query\Filter\ParserException;
use LdapRecord\Query\Model\Builder;
use Passbolt\DirectorySync\Test\TestCase\Utility\LdapDirectoryTest;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\LdapDirectory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * LdapDirectoryMock class
 *
 * @package Passbolt\DirectorySync\Test\Mock
 */
class LdapDirectoryMock
{
    /**
     * @var MockObject|LdapDirectory
     */
    public $Ldap;
    /**
     * @var DirectoryOrgSettings
     */
    public $settings;

    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * Constructor
     */
    private function __construct(TestCase $testCase, DirectoryOrgSettings $settings, array $config)
    {
        $this->testCase = $testCase;
        $this->settings = $settings;
        $this->Ldap = $this->testCase->getMockBuilder(LdapDirectory::class)
            ->onlyMethods($config['methods'])
            ->setConstructorArgs([$this->settings, false])
            ->getMock();
    }

    /**
     * Create default instance
     *
     * @param TestCase $testCase
     * @param DirectoryOrgSettings $settings
     * @return LdapDirectoryMock
     */
    public static function createDefault(TestCase $testCase, DirectoryOrgSettings $settings): LdapDirectoryMock
    {
        $methods = ['getConnection', 'fetchDirectoryData', 'getQuery', '_fetchAndInitializeQuery', '_fetchAndInitializeUsersQuery', '_fetchAndInitializeGroupsQuery'];
        $mock = new self($testCase, $settings, ['methods' => $methods]);
        $mock->mockConnection();

        return $mock;
    }

    /**
     * Create instance allowing fetch directory mock data
     *
     * @param TestCase $testCase
     * @param DirectoryOrgSettings $settings
     * @return LdapDirectoryMock
     */
    public static function createAllowingFetch(TestCase $testCase, DirectoryOrgSettings $settings): LdapDirectoryMock
    {
        $methods = ['getConnection', 'getQuery'];
        $mock = new self($testCase, $settings, ['methods' => $methods]);
        $mock->mockConnection();

        return $mock;
    }

    /**
     * Create instance without actually getting results
     *
     * @param TestCase $testCase
     * @param DirectoryOrgSettings $settings
     * @return LdapDirectoryMock
     */
    public static function createWithoutResultsProcessing(TestCase $testCase, DirectoryOrgSettings $settings): LdapDirectoryMock
    {
        $methods = ['getConnection', 'getFilteredDirectoryResults'];
        $mock = new self($testCase, $settings, ['methods' => $methods]);
        $mock->mockConnection();

        return $mock;
    }

    /**
     * Prepare mock object to fake connection
     *
     * @return void
     */
    public function mockConnection()
    {
        $configuration = $this->testCase->getMockBuilder(DomainConfiguration::class)->getMock();
        $configuration->expects($this->testCase->any())
            ->method('get')
            ->with()
            ->willReturnMap([['base_dn', LdapDirectoryTest::BASE_DN], ['domain', LdapDirectoryTest::TEST_DOMAIN]]);

        $connection = $this->testCase->getMockBuilder(Connection::class)->getMock();
        $connection->expects($this->testCase->any())
            ->method('getConfiguration')
            ->willReturn($configuration);
        $this->Ldap->expects($this->testCase->any())
            ->method('getConnection')
            ->willReturn($connection);

        $this->Ldap->initializeContainer($this->settings->getLdapSettings());
    }

    /**
     * Generate empty directory results mock
     *
     * @return MockObject
     */
    public function generateDirectoryResultsMock(): MockObject
    {
        $directoryResults = $this->testCase->getMockBuilder(DirectoryResults::class)
            ->onlyMethods(['getUsersAsArray', 'getGroupsAsArray', 'getRecursivelyFromParentGroup', 'initializeWithLdapResults'])
            ->disableOriginalConstructor()
            ->getMock();

        return $directoryResults;
    }

    /**
     * Set users collection on directory result mock
     *
     * @param MockObject $directoryResults
     * @param array|null $expectedUsers
     * @return void
     */
    public function setDirectoryResultsUsersExpectation(MockObject $directoryResults, ?array $expectedUsers = null)
    {
        if (!$expectedUsers) {
            $directoryResults->expects($this->testCase->never())
                ->method('getUsersAsArray');

            return;
        }
        $directoryResults->expects($this->testCase->once())
            ->method('getUsersAsArray')
            ->willReturn($expectedUsers);
    }

    /**
     * Set groups collection on directory result mock
     *
     * @param MockObject $directoryResults
     * @param array|null $expectedGroups
     * @return void
     */
    public function setDirectoryResultsGroupsExpectation(MockObject $directoryResults, ?array $expectedGroups = null)
    {
        if (!$expectedGroups) {
            $directoryResults->expects($this->testCase->never())
                ->method('getGroupsAsArray');

            return;
        }
        $directoryResults->expects($this->testCase->once())
            ->method('getGroupsAsArray')
            ->willReturn($expectedGroups);
    }

    /**
     * Set single expectation to filter directory results using getRecursivelyFromParentGroup
     *
     * @param MockObject $directoryResults
     * @param string $type
     * @param string $group
     * @param MockObject $filtered
     * @return void
     */
    public function setDirectoryResultsRecursivelyFromParentGroupExpectation(MockObject $directoryResults, string $type, string $group, MockObject $filtered)
    {
        $directoryResults->expects($this->testCase->once())
            ->method('getRecursivelyFromParentGroup')
            ->with($type, $group)
            ->willReturn($filtered);
    }

    /**
     * Set consecutive expectation to filter directory results using getRecursivelyFromParentGroup
     *
     * @param MockObject $directoryResults
     * @param array $parameters
     * @param array $filtered
     * @return void
     */
    public function setDirectoryResultsRecursivelyFromParentGroupConsecutiveExpectation(MockObject $directoryResults, array $parameters, array $filtered)
    {
        $directoryResults->expects($this->testCase->exactly(count($parameters)))
            ->method('getRecursivelyFromParentGroup')
            ->withConsecutive(...$parameters)
            ->willReturnOnConsecutiveCalls(...$filtered);
    }

    /**
     * Set fetchDirectoryData expectation
     *
     * @param MockObject $directoryResults
     * @return void
     */
    public function setFetchDirectoryDataExpectation(MockObject $directoryResults)
    {
        $this->Ldap->expects($this->testCase->once())
            ->method('fetchDirectoryData')
            ->willReturn($directoryResults);
    }

    /**
     * Set getQuery expectation
     *
     * @return void
     */
    public function setGetQueryExpectation()
    {
        $mappingRules = $this->settings->getFieldsMapping(DirectoryInterface::TYPE_AD);
        $builder = $this->testCase->getMockBuilder(Builder::class)
            ->onlyMethods(['select', 'setBaseDn', 'where', 'paginate', 'rawFilter'])
            ->disableOriginalConstructor()
            ->getMock();

        $groupCustomFilter = $this->settings->getGroupCustomFilters();
        $userCustomFilter = $this->settings->getUserCustomFilters();
        $validGroupCustomFilter = !is_callable($groupCustomFilter);
        $validUserCustomFilter = !is_callable($userCustomFilter);
        $getExpectationCount = 0;
        if ($validGroupCustomFilter && $this->settings->getGroupCustomFilters()) {
            try {
                Parser::parse($groupCustomFilter);
            } catch (ParserException $pe) {
                $validGroupCustomFilter = false;
                $getExpectationCount = 0;
            }
        }
        if ($validUserCustomFilter && $this->settings->getUserCustomFilters()) {
            try {
                Parser::parse($userCustomFilter);
            } catch (ParserException $pe) {
                $validUserCustomFilter = false;
                $getExpectationCount = 1;
            }
        }

        $rawFilterConditions = [];
        if ($this->settings->getEnabledUsersOnly()) {
            $rawFilterConditions[] = [DirectoryInterface::AD_ENABLED_USERS_FILTER];
        }
        $expectationCount = 1;
        if ($validGroupCustomFilter && $validUserCustomFilter) {
            if ($this->settings->getGroupCustomFilters()) {
                array_unshift($rawFilterConditions, [$this->settings->getGroupCustomFilters()]);
            }
            if ($this->settings->getUserCustomFilters()) {
                $rawFilterConditions[] = [$this->settings->getUserCustomFilters()];
            }
            $expectationCount = 2;
            $getExpectationCount = 2;
        } elseif (!$validUserCustomFilter) {
            $expectationCount = 2;
            $getExpectationCount = 1;
        }
        if ($rawFilterConditions) {
            $builder->expects($this->testCase->exactly(count($rawFilterConditions)))
                ->method('rawFilter')
                ->withConsecutive(...$rawFilterConditions)
                ->willReturnSelf();
        } else {
            $builder->expects($this->testCase->never())
                ->method('rawFilter');
        }
        $ldapGroup = $this->testCase->getMockBuilder(Group::class)
            ->onlyMethods(['addAttributeValue'])
            ->getMock();
        $ldapGroup->expects($this->testCase->exactly($validGroupCustomFilter ? 2 : 0))
            ->method('addAttributeValue')
            ->withConsecutive(['objectType', DirectoryInterface::ENTRY_TYPE_GROUP], ['directoryType', DirectoryInterface::TYPE_AD]);
        $ldapUser = $this->testCase->getMockBuilder(User::class)
            ->onlyMethods(['addAttributeValue', 'isEnabled'])
            ->getMock();

        $ldapUser->expects($this->testCase->exactly($validGroupCustomFilter && $validUserCustomFilter ? 2 : 0))
            ->method('addAttributeValue')
            ->withConsecutive(['objectType', DirectoryInterface::ENTRY_TYPE_USER], ['directoryType', DirectoryInterface::TYPE_AD]);

        $builder->expects($this->testCase->exactly($getExpectationCount))
            ->method('paginate')
            ->willReturn([$ldapGroup], [$ldapUser]);
        $builder->expects($this->testCase->exactly($expectationCount))
            ->method('select')
            ->withConsecutive(
                [array_values($mappingRules[DirectoryInterface::ENTRY_TYPE_GROUP])],
                [array_values($mappingRules[DirectoryInterface::ENTRY_TYPE_USER])]
            )
            ->willReturnSelf();
        $builder->expects($this->testCase->exactly($expectationCount))
            ->method('setBaseDn')
            ->withConsecutive([$this->Ldap->getDNFullPath(DirectoryInterface::ENTRY_TYPE_GROUP)], [$this->Ldap->getDNFullPath(DirectoryInterface::ENTRY_TYPE_USER)])
            ->willReturnSelf();
        $this->Ldap->expects($this->testCase->exactly($expectationCount))
            ->method('getQuery')
            ->withConsecutive([DirectoryInterface::ENTRY_TYPE_GROUP], [DirectoryInterface::ENTRY_TYPE_USER])
            ->willReturn($builder);
    }

    /**
     * Set getFilteredDirectoryResults expectation
     *
     * @param MockObject $directoryResults
     * @return void
     */
    public function setFilteredDirectoryResultsExpectation(MockObject $directoryResults)
    {
        $this->Ldap->expects($this->testCase->once())
            ->method('getFilteredDirectoryResults')
            ->willReturn($directoryResults);
    }

    /**
     * Set fetchAndInitializeQuery result based on entry type
     *
     * @param string $return
     * @return void
     */
    public function setFetchAndInitializeQueryExpectation(string $entryType)
    {
        $builder = $this->testCase->getMockBuilder(Builder::class)
            ->onlyMethods(['getUnescapedQuery'])
            ->disableOriginalConstructor()
            ->getMock();
        $builder->expects($this->testCase->once())
            ->method('getUnescapedQuery')
            ->willReturn($entryType);
        $this->Ldap->expects($this->testCase->once())
            ->method('_fetchAndInitializeQuery')
            ->with($entryType)
            ->willReturn($builder);
    }
}
