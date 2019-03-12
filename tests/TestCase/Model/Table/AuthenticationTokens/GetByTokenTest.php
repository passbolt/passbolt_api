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

namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GetByTokenTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public $fixtures = ['app.Base/AuthenticationTokens', 'app.Base/Users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensGet
     */
    public function testAuthenticationTokensGetByTokenNotUuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->AuthenticationTokens->getByToken('nope');
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensGet
     */
    public function testAuthenticationTokensGetByTokenInactive()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $tokenInactive = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'inactive');
        $t = $this->AuthenticationTokens->getByToken($tokenInactive);
        $this->assertEmpty($t);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensGet
     */
    public function testAuthenticationTokensGetByTokenExpiredSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $tokenExpired = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'expired');
        $t = $this->AuthenticationTokens->getByToken($tokenExpired);
        $this->assertAuthTokenAttributes($t);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensGet
     */
    public function testAuthenticationTokensGetByTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_LOGIN);
        $t = $this->AuthenticationTokens->getByToken($t->token);
        $this->assertAuthTokenAttributes($t);
    }
}
