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

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class RecoverStartControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles', 'app.Base/authentication_tokens'
    ];
    public $AuthenticationTokens;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        parent::setUp();
    }

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
        }
    }

    public function testRecoverStartBadRequestError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
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
        }
    }

    public function testRecoverStartBadRequestErrorExpiredToken()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.expired'));
        $url = '/setup/install/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was expired');
    }

    public function testRecoverStartBadRequestErrorInactiveToken()
    {
        // Create a valid token for ruth and set it to inactive
        $userId = UuidFactory::uuid('user.id.ruth');
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
        $t->active = false;
        $this->AuthenticationTokens->save($t);

        $url = '/setup/recover/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    public function testRecoverStartBadRequestErrorInactiveAndExpiredToken()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.expired_inactive'));
        $url = '/setup/recover/start/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when token was already used.');
    }

    public function testRecoverStartBadRequestErrorInactiveUser()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.ruth'));
        $url = '/setup/recover/start/' . UuidFactory::uuid('user.id.ruth') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has not completed setup.');
    }

    public function testRecoverStartBadRequestErrorDeletedUser()
    {
        $t = $this->AuthenticationTokens->get(UuidFactory::uuid('token.id.sofia'));
        $url = '/setup/recover/start/' . UuidFactory::uuid('user.id.sofia') . '/' . $t->token;
        $this->get($url);
        $this->assertResponseCode(400, 'Setup start should fail with 400 when user has been deleted.');
    }

    public function testRecoverStartSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ada'));
        $url = '/setup/recover/start/' . UuidFactory::uuid('user.id.ada') . '/' . $t->token;

        $this->get($url);
        $this->assertResponseOk();
        $this->assertResponseContains('Account recovery: let\'s take 5 min to reconfigure your plugin!<');
    }
}
