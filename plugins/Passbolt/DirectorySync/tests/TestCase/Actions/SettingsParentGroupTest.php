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

use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Cake\Core\Configure;

class SettingsParentGroupTest extends DirectorySyncIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
        $this->userSyncAction = new UserSyncAction();
        $this->groupSyncAction = new GroupSyncAction();
    }

    public function testSettingUserParentGroup() {

    }
}