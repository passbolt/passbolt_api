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
 * @since         2.14.0
 */

namespace App\Test\TestCase\Model\Table\Users\Finders;

use App\Model\Table\UsersTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTestTrait;

class FindLastLoggedInTest extends AppTestCase
{
    use ActionLogsTestTrait;

    /**
     * @var UsersTable
     */
    private $usersTable;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->usersTable = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * Tear down
     */
    public function tearDown(): void
    {
        unset($this->usersTable);

        parent::tearDown();
    }

    public function testFindLastLoggedIn()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $userId = $user->get('id');
        $actionLogOld = ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(1)])
            ->loginAction()
            ->userId($userId)
            ->persist();
        $actionLogLatest = ActionLogFactory::make(['created' => FrozenTime::now()])
            ->loginAction()
            ->userId($userId)
            ->persist();

        $result = $this->usersTable->findById($userId)->find('lastLoggedIn')->first();

        $this->assertObjectHasAttribute('last_logged_in', $result);
        $this->assertGreaterThan($actionLogOld->get('created'), $result->get('last_logged_in'));
        $this->assertSame($actionLogLatest->get('created')->i18nFormat(), $result->get('last_logged_in')->i18nFormat());
    }

    public function testFindLastLoggedIn_JwtAuth()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $userId = $user->get('id');
        // auth login logs
        ActionLogFactory::make(['created' => FrozenTime::now()->subMonths(2)])
            ->loginAction()
            ->userId($userId)
            ->persist();
        ActionLogFactory::make(['created' => FrozenTime::now()->subDays(2)])
            ->loginAction()
            ->userId($userId)
            ->persist();
        // jwt login logs
        $actionLogJwtOld = ActionLogFactory::make(['created' => FrozenTime::now()->subHours(2)])
            ->setActionId('JwtLogin.loginPost')
            ->userId($userId)
            ->persist();
        $actionLogJwtLatest = ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(1)])
            ->setActionId('JwtLogin.loginPost')
            ->userId($userId)
            ->persist();

        $result = $this->usersTable->findById($userId)->find('lastLoggedIn')->first();

        $this->assertObjectHasAttribute('last_logged_in', $result);
        $this->assertGreaterThan($actionLogJwtOld->get('created'), $result->get('last_logged_in'));
        $this->assertSame($actionLogJwtLatest->get('created')->i18nFormat(), $result->get('last_logged_in')->i18nFormat());
    }
}
