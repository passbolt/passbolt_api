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
 * @since         4.4.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Model\Table\UsersTable;
use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;

class UserSyncActionAddCaseSensitiveTest extends DirectorySyncIntegrationTestCase
{
    /**
     * @var array No fixtures in this test case, using factories
     */
    public $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        $this->action = new UserSyncAction(
            new ResourcesExpireResourcesFallbackServiceService()
        );
        $this->action->getDirectory()->setUsers([]);
    }

    public function testDirectorySyncUserAdd_Existing_Username_Case_Insensitive_Should_Map_On_Existing_User()
    {
        // Creating a deleted user. This user must be reported as already existing
        $created = FrozenTime::yesterday();
        $username = 'JOHN@passbolt.com';
        UserFactory::make(compact('username', 'created'))->user()->persist();
        $this->mockDirectoryUserData(
            'foo',
            'bar',
            $username,
            $created->subDays(1),
            $created->subDays(1),
        );
        $reports = $this->action->execute();
        $message = $reports[0]->getMessage();
        $this->assertSame(
            "The user $username was mapped with an existing user in passbolt.",
            $message,
        );
        $this->assertSame(2, UserFactory::count());
    }

    public function testDirectorySyncUserAdd_Existing_Username_Case_Sensitive_Should_Create_A_New_User()
    {
        Configure::write(UsersTable::PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE, true);
        // Creating a deleted user. This user must be reported as already existing
        $created = FrozenTime::yesterday();
        $username = 'JOHN@passbolt.com';
        UserFactory::make(compact('username', 'created'))->user()->persist();
        $this->mockDirectoryUserData(
            'foo',
            'bar',
            $username,
            $created->subDays(1),
            $created->subDays(1),
        );
        $reports = $this->action->execute();
        $message = $reports[0]->getMessage();
        $this->assertSame(
            'The user john@passbolt.com was successfully added to passbolt.',
            $message,
        );
        $this->assertSame(3, UserFactory::count());
    }
}
