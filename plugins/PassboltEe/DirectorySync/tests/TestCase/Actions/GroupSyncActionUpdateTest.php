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
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\Alias;

class GroupSyncActionUpdateTest extends DirectorySyncIntegrationTestCase
{
    use AssertGroupsTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->initAction();
    }

    /**
     * Init action
     *
     * @return void
     */
    private function initAction(): void
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
    }

    /**
     * Scenario: Group was added in ldap, then renamed
     * Expected result: Group should be renamed as id matches in both ldap and directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupUpdate
     */
    public function testDirectorySyncGroup_Case49a_OkRenamed_Success_Ok()
    {
        $group = $this->mockDirectoryGroupData('newFromLdap');
        $this->action->execute();
        $updatedGroup = $this->mockDirectoryGroupData('newFromLdapRenamed');
        $updatedGroup['id'] = $group['id'];
        $this->action->getDirectory()->setGroups([]);
        $this->saveMockDirectoryGroupData($updatedGroup);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_UPDATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[1], $expectedReport);
        $data = $reports[1]->getData();
        $this->assertGroupExist($data->id, ['name' => 'newFromLdapRenamed', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExistsForGroup(['name' => 'newFromLdapRenamed', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group was added in ldap, then renamed but sync.groups.update is disabled
     * Expected result: Don't rename group because sync.groups.update is disabled
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupUpdate
     */
    public function testDirectorySyncGroup_Case49b_OkRenamed_Success_keepPrevious()
    {
        $this->disableSyncOperation('groups', Alias::ACTION_UPDATE);
        $this->initAction();
        $group = $this->mockDirectoryGroupData('newFromLdap');
        $this->action->execute();
        $updatedGroup = $this->mockDirectoryGroupData('newFromLdapRenamed');
        $updatedGroup['id'] = $group['id'];
        $this->action->getDirectory()->setGroups([]);
        $this->saveMockDirectoryGroupData($updatedGroup);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertEmpty($reports[1]);
        $data = $reports[0]->getData();
        $this->assertGroupNotExist(null, ['name' => 'newFromLdapRenamed', 'deleted' => false]);
        $this->assertGroupExist($data->id, ['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExistsForGroup(['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group was added in ldap, then renamed but name is invalid
     * Expected result: Don't rename group because new name is invalid
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupUpdate
     */
    public function testDirectorySyncGroup_Case49c_Invalid_Error_keepPrevious()
    {
        $invalidGroupName = str_repeat('group', 256);
        $group = $this->mockDirectoryGroupData('newFromLdap');
        $this->action->execute();
        $updatedGroup = $this->mockDirectoryGroupData($invalidGroupName);
        $updatedGroup['id'] = $group['id'];
        $this->action->getDirectory()->setGroups([]);
        $this->saveMockDirectoryGroupData($updatedGroup);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertNotEmpty($reports[1]);
        $this->assertSame(Alias::STATUS_ERROR, $reports[1]->getStatus());
        $this->assertSame(__('The group newFromLdap could not be renamed to {0}.', $invalidGroupName), $reports[1]->getMessage());
        $data = $reports[0]->getData();
        $this->assertGroupNotExist(null, ['name' => $invalidGroupName, 'deleted' => false]);
        $this->assertGroupExist($data->id, ['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExistsForGroup(['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }
}
