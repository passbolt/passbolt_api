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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GenerateTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public $fixtures = ['app.Base/AuthenticationTokens', 'app.Base/Users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
    }

    public function testAuthenticationTokensGenerateWrongUserId()
    {
        $this->expectException(ValidationException::class);
        $this->AuthenticationTokens->generate('nope', AuthenticationToken::TYPE_LOGIN);
    }

    public function testAuthenticationTokensLoginGenerateUserIdNotExist()
    {
        $this->expectException(ValidationException::class);
        $userId = UuidFactory::uuid('user.id.nope');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_LOGIN);
    }

    public function testAuthenticationTokensRegisterGenerateUserIdNotExist()
    {
        $this->expectException(ValidationException::class);
        $userId = UuidFactory::uuid('user.id.nope');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
    }

    public function testAuthenticationTokensLoginGenerateDeletedUserIdError()
    {
        // Sofia is deleted it should not be possible to create a token
        $this->expectException(ValidationException::class);
        $userId = UuidFactory::uuid('user.id.sofia');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_LOGIN);
    }

    public function testAuthenticationTokensRegisterGenerateDeletedUserIdError()
    {
        // Sofia is deleted it should not be possible to create a token
        $this->expectException(ValidationException::class);
        $userId = UuidFactory::uuid('user.id.sofia');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
    }

    public function testAuthenticationTokensGenerateActiveUserIdSuccess()
    {
        // Ada is active it should be possible to create a token (e.g. login token)
        $userId = UuidFactory::uuid('user.id.ada');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_LOGIN);
        $this->assertAuthTokenAttributes($token);
    }

    public function testAuthenticationTokensGenerateInactiveUserIdSuccess()
    {
        // Ruth is inactive it should be possible to create a token (e.g. setup token)
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
        $this->assertAuthTokenAttributes($token);
    }
}
