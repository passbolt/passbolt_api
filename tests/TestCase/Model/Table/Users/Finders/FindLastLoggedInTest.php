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
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTrait;

class FindLastLoggedInTest extends AppIntegrationTestCase
{
    use ActionLogsTrait;

    public $fixtures = [
        UsersFixture::class,
    ];

    /**
     * @var UsersTable
     */
    private $usersTable;

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->usersTable = TableRegistry::getTableLocator()->get('Users', $config);
    }

    public function testFindLastLoggedIn()
    {
        [$actionLogAdaLogin1, $actionLogAdaLogin2, $userAId] = $this->insertFixture_FindLastLoggedIn();

        $userA = $this->usersTable->findById($userAId)->find('lastLoggedIn')->first();
        $this->assertNotEmpty($userA->last_logged_in);
        $this->assertGreaterThan($actionLogAdaLogin1->created, $userA->last_logged_in);
    }

    private function insertFixture_FindLastLoggedIn()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $loginActionId = UuidFactory::uuid('AuthLogin.loginPost');

        // Add logged in in the determined period.
        $actionLogAdaLogin1Data = [
            'user_id' => $userAId,
            'action_id' => $loginActionId,
            'context' => 'POST /auth/login.json',
            'status' => 1,
            'created' => (new \DateTime())->modify('-1 second'),
        ];
        $actionLogAdaLogin1 = $this->addActionLog($actionLogAdaLogin1Data);

        $actionLogAdaLogin2Data = $actionLogAdaLogin1Data;
        $actionLogAdaLogin2Data['created'] = new \DateTime();
        $actionLogAdaLogin2 = $this->addActionLog($actionLogAdaLogin2Data);

        return [$actionLogAdaLogin1, $actionLogAdaLogin2, $userAId];
    }
}
