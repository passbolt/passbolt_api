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

class GetByTokenTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public $fixtures = ['app.Base/authentication_tokens', 'app.Base/users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
    }

    public function testGetByTokenNotUuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->AuthenticationTokens->getByToken('nope');
    }

    public function testGetByTokenInactive()
    {
        $t = $this->AuthenticationTokens->getByToken(UuidFactory::uuid('token.id.inactive'));
        $this->assertEmpty($t);
    }

    public function testGetByTokenExpiredSuccess()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.expired'));
        $t = $this->AuthenticationTokens->getByToken($t->token);
        $this->assertAuthTokenAttributes($t);
    }

    public function testGetByTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $t = $this->AuthenticationTokens->getByToken($t->token);
        $this->assertAuthTokenAttributes($t);
    }
}
