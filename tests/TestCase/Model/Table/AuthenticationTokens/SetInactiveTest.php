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

class SetInactiveTest extends AppTestCase
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
     * @group AuthenticationTokenSetInactive
     */
    public function testAuthenticationTokensSetInactiveInvalidToken()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->AuthenticationTokens->setInactive('nope');
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokenSetInactive
     */
    public function testAuthenticationTokensSetInactiveAlreadyInactiveToken()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_REGISTER);
        $t->active = false;
        $this->AuthenticationTokens->save($t);
        $result = $this->AuthenticationTokens->setInactive($t->token);
        $this->assertFalse($result);
    }

    /**
     * @group model
     * @group AuthenticationTokens
     * @group AuthenticationTokenSetInactive
     */
    public function testAuthenticationTokensSetInactiveTokenSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_REGISTER);
        $result = $this->AuthenticationTokens->setInactive($t->token);
        $this->assertTrue($result);
        $t = $this->AuthenticationTokens->get($t->id);
        $this->assertFalse($t->active);
    }
}
