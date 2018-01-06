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

class SetInactiveTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public $fixtures = ['app.Base/authentication_tokens', 'app.Base/users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
    }

    public function testSetInactiveInvalidToken()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->AuthenticationTokens->setInactive('nope');
    }

    public function testSetInactiveAlreadyInactiveToken()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $t->active = false;
        $this->AuthenticationTokens->save($t);
        $result = $this->AuthenticationTokens->setInactive($t->token);
        $this->assertFalse($result);
    }

    public function testSetInactiveTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $result = $this->AuthenticationTokens->setInactive($t->token);
        $this->assertTrue($result);
        $t = $this->AuthenticationTokens->get($t->id);
        $this->assertFalse($t->active);
    }
}
