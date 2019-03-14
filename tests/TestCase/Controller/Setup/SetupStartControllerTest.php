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

class SetupStartControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/AuthenticationTokens'];
    public $AuthenticationTokens;
    use AuthenticationTokenModelTrait;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        parent::setUp();
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartUrlParametersMissingError()
    {
        $fails = [
            'no parameter given' => '/setup/start',
            'only one parameter given' => '/setup/start/' . UuidFactory::uuid(),
            'no parameter given on legacy url' => '/setup/install',
            'only one parameter given on legacy url' => '/setup/install/' . UuidFactory::uuid(),
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(404, 'Setup start should fail with 404 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartBadRequestError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER);
        $t2 = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_LOGIN);
        $fails = [
            'user not a uuid' => '/setup/start/nope/' . UuidFactory::uuid(),
            'user not a uuid with legacy url' => '/setup/install/nope/' . UuidFactory::uuid(),
            'token not a uuid' => '/setup/start/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'token not a uuid with legacy url' => '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'both not a uuid' => '/setup/install/nope/nope',
            'user does not exist' => '/setup/install/' . UuidFactory::uuid('user.id.nope') . '/' . $t->token,
            'token does not exist' => '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . UuidFactory::uuid(),
            'token from other user' => '/setup/install/' . UuidFactory::uuid('user.id.ada') . '/' . $t->token,
            'token is of wrong type' => '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t2->token,
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(400, 'Setup start should fail with 400 on case: ' . $case);
            $this->_response = null; // Free the memory usage.
        }
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartBadRequestErrorExpiredToken()
    {
        $token = $this->quickDummyAuthToken(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER, 'expired');
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was expired');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartBadRequestErrorInactiveToken()
    {
        $token = $this->quickDummyAuthToken(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER, 'inactive');
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');

        $token = $this->quickDummyAuthToken(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER, 'expired_inactive');
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartBadRequestErrorAlreadyActiveUser()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
        $url = '/setup/install/' . $userId . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has already completed setup.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartBadRequestErrorDeletedUser()
    {
        // Build the token manually as generate do not allow creating token for deleted users
        $userId = UuidFactory::uuid('user.id.sofia');
        $token = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'inactive');
        $url = '/setup/install/' . $userId . '/' . $token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has been deleted.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_REGISTER);
        $url = '/setup/install/' . $userId . '/' . $t->token;
        $this->get($url);
        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to passbolt! Let\'s take 5 min to setup your system.');
    }
}
