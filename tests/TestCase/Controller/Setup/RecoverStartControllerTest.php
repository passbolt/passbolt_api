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
namespace App\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class RecoverStartControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/AuthenticationTokens'
    ];
    public $AuthenticationTokens;
    use AuthenticationTokenModelTrait;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        parent::setUp();
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartUrlParametersMissingError()
    {
        $fails = [
            'no parameter given' => '/setup/recover/nope',
            // Add when legacy urls removed (/setup/recover/nope/nope get called instead of 404)
            //'only one parameter given' => '/setup/recover/start/' . UuidFactory::uuid(),
            'no parameter given on legacy url' => '/setup/recover',
            'only one parameter given on legacy url' => '/setup/recover/' . UuidFactory::uuid()
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
    public function testRecoverStartBadRequestError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_RECOVER);
        $fails = [
            'user not a uuid' => '/setup/recover/start/nope/nope',
            'user not a uuid with legacy url' => '/setup/recover/nope/nope',
            'token not a uuid' => '/setup/recover/start/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'token not a uuid with legacy url' => '/setup/recover/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'both not a uuid' => '/setup/recover/nope/nope',
            'user does not exist' => '/setup/recover/start/' . UuidFactory::uuid('user.id.nope') . '/' . $t->token,
            'token does not exist' => '/setup/recover/start/' . UuidFactory::uuid('user.id.ruth') . '/' . UuidFactory::uuid(),
            'token from other user' => '/setup/recover/start/' . UuidFactory::uuid('user.id.ada') . '/' . $t->token,
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(400, 'Setup start should fail with 400 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartBadRequestErrorExpiredToken()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER, 'expired');
        $url = '/setup/install/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was expired');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartBadRequestErrorInactiveToken()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER, 'inactive');
        $url = '/setup/recover/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartBadRequestErrorInactiveAndExpiredToken()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER, 'expired_inactive');
        $url = '/setup/recover/start/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartBadRequestErrorInactiveUser()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER);
        $url = '/setup/recover/start/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has not completed setup.');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartBadRequestErrorDeletedUser()
    {
        $userId = UuidFactory::uuid('user.id.sofia');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER);
        $url = '/setup/recover/start/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has been deleted.');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverStart
     */
    public function testRecoverStartSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_RECOVER);
        $url = '/setup/recover/start/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseOk();
        $this->assertResponseContains('Account recovery: let\'s take 5 min to reconfigure your plugin!<');
    }
}
