<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Model\Table;

use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

/**
 * ScimUsersTableTest class
 */
class ScimUsersTableTest extends AppTestCase
{
    public function testScimUsersTable_SoftDelete_ScimEntries_Plugin_Loaded(): void
    {
        $this->loadPlugins([ScimPlugin::class]);
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry[] $scimEntries */
        $scimEntries = ScimEntryFactory::make(2)->withUser()->persist();
        $userToDelete = $scimEntries[0]->user;

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $UsersTable->softDelete($userToDelete);

        $deletedUser = $UsersTable->get($scimEntries[0]->user->id);
        $this->assertTrue($deletedUser->isDeleted());

        $deletedEntry = ScimEntryFactory::get($scimEntries[0]->id);
        $nonDeletedEntry = ScimEntryFactory::get($scimEntries[1]->id);
        $this->assertNotNull($deletedEntry->get('deleted'));
        $this->assertNull($nonDeletedEntry->get('deleted'));
    }

    public function testScimUsersTable_SoftDelete_ScimEntries_Plugin_Not_Loaded(): void
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry[] $scimEntries */
        $scimEntries = ScimEntryFactory::make(2)->withUser()->persist();
        $userToDelete = $scimEntries[0]->user;

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $UsersTable->softDelete($userToDelete);

        $deletedUser = $UsersTable->get($scimEntries[0]->user->id);
        $this->assertTrue($deletedUser->isDeleted());

        $deletedEntry = ScimEntryFactory::get($scimEntries[0]->id);
        $nonDeletedEntry = ScimEntryFactory::get($scimEntries[1]->id);
        $this->assertNull($deletedEntry->get('deleted'));
        $this->assertNull($nonDeletedEntry->get('deleted'));
    }
}
