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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class IsValidTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public function setUp(): void
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
        $user = UserFactory::make()->persist();
        $result = $this->AuthenticationTokens->isValid(UuidFactory::uuid(), $user->id);
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidUserDoesNotExistFail()
    {
        $user = UserFactory::make()->persist();
        $t = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
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
        [$userA, $userB] = UserFactory::make(2)->persist();
        $t = $this->AuthenticationTokens->generate($userA->id, AuthenticationToken::TYPE_REGISTER);
        $result = $this->AuthenticationTokens->isValid($t->token, $userB->id);
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenInactiveFail()
    {
        $user = UserFactory::make()->persist();
        $tokenInactive = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_LOGIN)
            ->userId($user->id)
            ->inactive()
            ->persist();

        $result = $this->AuthenticationTokens->isValid($tokenInactive->token, $user->id);

        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenExpiredFail()
    {
        $user = UserFactory::make()->persist();
        $tokenInactive = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->expired()
            ->active()
            ->persist();

        $result = $this->AuthenticationTokens->isValid($tokenInactive->token, $user->id);

        $this->assertFalse($result);

        // token should now be marked as inactive
        $token = $this->AuthenticationTokens->find('all')->where(['token' => $tokenInactive->token])->first();
        $this->assertFalse($token->active);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokensIsValid
     */
    public function testAuthenticationTokensIsValidTokenSuccess()
    {
        $user = UserFactory::make()->persist();
        $t = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_LOGIN);
        $result = $this->AuthenticationTokens->isValid($t->token, $user->id);
        $this->assertTrue($result);
    }
}
