<?php
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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Users\Finders;

use App\Model\Entity\AuthenticationToken;
use App\Model\Table\UsersTable;
use App\Test\Fixture\Base\AuthenticationTokensFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindIndexTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $fixtures = [
        AuthenticationTokensFixture::class,
        UsersFixture::class,
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->usersTable = TableRegistry::getTableLocator()->get('Users', $config);
    }

    public function testFindLastLoggedIn()
    {
        $userAId = UuidFactory::uuid('user.id.ada');

        $this->quickDummyAuthToken($userAId, AuthenticationToken::TYPE_LOGIN, 'inactive');
        $userA = $this->usersTable->findById($userAId)->find('lastLoggedIn')->first();
        $userALastLoggedInFirst = $userA->last_logged_in;
        $this->assertNotEmpty($userALastLoggedInFirst);
        sleep(1);

        $this->quickDummyAuthToken($userAId, AuthenticationToken::TYPE_LOGIN, 'inactive');
        $userA = $this->usersTable->findById($userAId)->find('lastLoggedIn')->first();
        $userALastLoggedInSecond = $userA->last_logged_in;
        $this->assertNotEmpty($userALastLoggedInSecond);
        $this->assertNotEquals($userALastLoggedInFirst, $userALastLoggedInSecond);
        $this->assertGreaterThan($userALastLoggedInFirst, $userALastLoggedInSecond);
    }
}
