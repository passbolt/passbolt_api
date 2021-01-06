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
namespace App\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class RecoverStartControllerTest extends AppIntegrationTestCase
{
    use AuthenticationTokenModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Avatars',
    ];
    public $AuthenticationTokens;
    private $Users;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        parent::setUp();
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStart_NotFoundError_MissingUrlParameters()
    {
        $fails = [
            'no parameter given' => '/setup/recover/nope',
            // Add when legacy urls removed (/setup/recover/nope/nope get called instead of 404)
            //'only one parameter given' => '/setup/recover/start/' . UuidFactory::uuid(),
            'no parameter given on legacy url' => '/setup/recover',
            'only one parameter given on legacy url' => '/setup/recover/' . UuidFactory::uuid(),
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(404, 'Setup start should fail with 404 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartJson_NotFoundError_MissingUrlParameters()
    {
        $fails = [
            'no parameter given' => '/setup/recover/nope.json',
            // Add when legacy urls removed (/setup/recover/nope/nope get called instead of 404)
            //'only one parameter given' => '/setup/recover/start/' . UuidFactory::uuid(),
            'no parameter given on legacy url' => '/setup/recover.json',
            'only one parameter given on legacy url' => '/setup/recover/' . UuidFactory::uuid() . '.json',
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(404, 'Setup start should fail with 404 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartJson_BadRequestError_InvalidParameters()
    {
        $fails = [
            'user not a uuid' => '/setup/recover/start/nope/nope.json',
            'user not a uuid with legacy url' => '/setup/recover/nope/nope.json',
            'token not a uuid' => '/setup/recover/start/' . UuidFactory::uuid('user.id.ruth') . '/nope.json',
            'token not a uuid with legacy url' => '/setup/recover/' . UuidFactory::uuid('user.id.ruth') . '/nope.json',
            'both not a uuid' => '/setup/recover/nope/nope.json',
        ];
        foreach ($fails as $case => $url) {
            $this->getJson($url);
            $this->assertResponseCode(400, 'Setup start should fail with 400 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStartJson_BadRequestError_UserInactive()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStartJson_BadRequestError_UserNotExist()
    {
        $userId = UuidFactory::uuid();
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.admin'), AuthenticationToken::TYPE_RECOVER);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStart_BadRequestError_UserDeleted()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $user = $this->Users->findById($userId)->first();
        $user->deleted = 1;
        $this->Users->save($user);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStart_BadRequestError_TokenDoesntExist()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $token = UuidFactory::uuid();
        $url = "/setup/recover/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStart_BadRequestError_WrongTokenType()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStart_BadRequestError_TokenAlreadyConsumed()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $t->active = false;
        $this->AuthenticationTokens->save($t);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStart_BadRequestError_TokenExpired()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $t->created = '2020-01-01 00:00:00';
        $this->AuthenticationTokens->save($t);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, 'token');
        $this->assertNotNull($error, 'The test should return an error for the given field.');
        $this->assertEquals('The token is expired.', $error['expired']);
    }

    /**
     * @group AN
     * @group setup
     * @group recoverStart
     */
    public function testRecoverStartJson_Success()
    {
        $userId = UuidFactory::uuid('user.id.admin');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $url = "/setup/recover/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseOk();
        $this->assertNotNull($this->_responseJsonBody->user);
        $this->assertUserAttributes($this->_responseJsonBody->user);
    }
}
