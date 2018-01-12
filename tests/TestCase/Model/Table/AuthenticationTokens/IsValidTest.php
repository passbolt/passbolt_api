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

namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Error\Exception\ValidationRuleException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class IsValidTest extends AppTestCase
{
    public $AuthenticationTokens;

    public $fixtures = ['app.Base/authentication_tokens', 'app.Base/users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
    }

    public function testIsValidWrongUserIdFail()
    {
        $result = $this->AuthenticationTokens->isValid('nope', UuidFactory::uuid());
        $this->assertFalse($result);
    }

    public function testIsValidWrongTokenIdFail()
    {
        $result = $this->AuthenticationTokens->isValid(UuidFactory::uuid(), 'nope');
        $this->assertFalse($result);
    }

    public function testIsValidTokenDoesNotExistFail()
    {
        $result = $this->AuthenticationTokens->isValid(UuidFactory::uuid(), UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($result);
    }

    public function testIsValidUserDoesNotExistFail()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid());
        $this->assertFalse($result);
    }

    public function testIsValidNotSameUserFail()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.betty'));
        $this->assertFalse($result);
    }

    public function testIsValidTokenInactiveFail()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $t->active = false;
        $this->AuthenticationTokens->save($t);
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($result);
    }

    public function testIsValidTokenExpiredFail()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.expired'));
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.ruth'));
        $this->assertFalse($result);

        // token should now be marked as inactive
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.expired'));
        $this->assertFalse($t->active);
    }

    public function testIsValidTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $result = $this->AuthenticationTokens->isValid($t->token, UuidFactory::uuid('user.id.ada'));
        $this->assertTrue($result);
    }
}
