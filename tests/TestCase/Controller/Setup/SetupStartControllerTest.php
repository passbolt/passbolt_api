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
namespace App\Test\TestCase\Controller\Setup;

use App\Utility\UuidFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;

class SetupStartControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.profiles', 'app.gpgkeys', 'app.roles', 'app.authentication_tokens'];
    public $AuthenticationTokens;
    
    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        parent::setUp();
    }

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
        }
    }

    public function testSetupStartBadRequestError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
        $fails = [
            'user not a uuid' => '/setup/start/nope/' . UuidFactory::uuid(),
            'user not a uuid with legacy url' => '/setup/install/nope/' . UuidFactory::uuid(),
            'token not a uuid' => '/setup/start/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'token not a uuid with legacy url' => '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/nope',
            'both not a uuid' => '/setup/install/nope/nope',
            'user does not exist' => '/setup/install/' . UuidFactory::uuid('user.id.nope') . '/' . $t->token,
            'token does not exist' => '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . UuidFactory::uuid(),
            'token from other user' => '/setup/install/' . UuidFactory::uuid('user.id.ada') . '/' . $t->token,
        ];
        foreach ($fails as $case => $url) {
            $this->get($url);
            $this->assertResponseCode(400, 'Setup start should fail with 400 on case: ' . $case);
        }

    }

    public function testSetupStartBadRequestErrorExpiredToken()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.expired')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was expired');
    }

    public function testSetupStartBadRequestErrorInactiveToken()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.inactive')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');

        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.expired_inactive')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    public function testSetupStartBadRequestErrorAlreadyActiveUser()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.ada')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ada') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has already completed setup.');
    }

    public function testSetupStartBadRequestErrorDeletedUser()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.sophia')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.sophia') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has been deleted.');
    }

    public function testSetupStartSuccess()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.ruth')])
            ->first();
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;

        $this->get($url);
        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to passbolt! Let\'s take 5 min to setup your system.');
    }
}