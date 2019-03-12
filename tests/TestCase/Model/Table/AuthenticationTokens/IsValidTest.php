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

class IsValidTest extends AppTestCase
{
    public $AuthenticationTokens;

    public $fixtures = ['app.Base/AuthenticationTokens', 'app.Base/Users'];
    use AuthenticationTokenModelTrait;

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidWrongUserIdFail()
    {
        $result = $this->AuthenticationTokens->isValid('nope', UuidFactory::uuid());
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidWrongTokenIdFail()
    {
        $result = $this->AuthenticationTokens->isValid(UuidFactory::uuid(), 'nope');
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenDoesNotExistFail()
    {
        $result = $this->AuthenticationTokens->isValid(UuidFactory::uuid(), UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidUserDoesNotExistFail()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_REGISTER);
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid());
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidNotSameUserFail()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_REGISTER);
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.betty'));
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenInactiveFail()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $tokenInactive = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_LOGIN, 'inactive');
        $result = $this->AuthenticationTokens->isValid($tokenInactive, UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenExpiredFail()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $tokenInactive = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'expired');
        $result = $this->AuthenticationTokens->isValid($tokenInactive, $userId);
        $this->assertFalse($result);

        // token should now be marked as inactive
        $token = $this->AuthenticationTokens->find('all')->where(['token' => $tokenInactive])->first();
        $this->assertFalse($token->active);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_LOGIN);
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.ada'));
        $this->assertTrue($result);
    }
}
