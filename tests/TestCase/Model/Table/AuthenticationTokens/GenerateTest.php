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

class GenerateTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;

    public $AuthenticationTokens;

    public $fixtures = ['app.Base/authentication_tokens', 'app.Base/users'];

    public function setUp()
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
    }

    public function testGenerateWrongUserId()
    {
        $this->expectException(ValidationRuleException::class);
        $this->AuthenticationTokens->generate('nope');
    }

    public function testGenerateUserIdNotExist()
    {
        $this->expectException(ValidationRuleException::class);
        $userId = UuidFactory::uuid('user.id.nope');
        $token = $this->AuthenticationTokens->generate($userId);
    }

    public function testGenerateDeletedUserIdError()
    {
        // Sofia is deleted it should not be possible to create a token
        $this->expectException(ValidationRuleException::class);
        $userId = UuidFactory::uuid('user.id.sofia');
        $token = $this->AuthenticationTokens->generate($userId);
    }

    public function testGenerateActiveUserIdSuccess()
    {
        // Ada is active it should be possible to create a token (e.g. login token)
        $userId = UuidFactory::uuid('user.id.ada');
        $token = $this->AuthenticationTokens->generate($userId);
        $this->assertAuthTokenAttributes($token);
    }

    public function testGenerateInactiveUserIdSuccess()
    {
        // Ruth is inactive it should be possible to create a token (e.g. setup token)
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->AuthenticationTokens->generate($userId);
        $this->assertAuthTokenAttributes($token);
    }
}
