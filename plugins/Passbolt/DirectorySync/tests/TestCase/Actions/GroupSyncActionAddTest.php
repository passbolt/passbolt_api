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
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Utility\UuidFactory;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Actions\GroupSyncAction;

class GroupSyncActionTest extends DirectorySyncTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',  'app.Base/roles',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
    ];

    /**
     * Scenario: Group was added in passbolt and not in ldap
     * Expected result: Do nothing
     */
    public function testGroupSync_Case00_02_Null_Null_Ok()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);

        // Nothing should happen
        $this->action->execute();
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 0);
    }
}