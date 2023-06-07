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
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class SetupStartControllerTest extends AppIntegrationTestCase
{
    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_HTML_Success(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $url = "/setup/install/{$userId}/{$t->token}";
        $this->get($url);
        $this->assertResponseOk();
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_HTML_Error_NotFound_MissingUrlParameters(): void
    {
        $fails = [
            'no parameter given' => '/setup/start',
            'only one parameter given' => '/setup/start/' . UuidFactory::uuid(),
            //'no parameter given on legacy url' => '/setup/install',
            //'only one parameter given on legacy url' => '/setup/install/' . UuidFactory::uuid(),
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
    public function testSetupStartController_Success(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $url = "/setup/start/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseOk();
        $this->assertNotNull($this->_responseJsonBody->user);
        $this->assertUserAttributes($this->_responseJsonBody->user);
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_NotFound_MissingUrlParameters(): void
    {
        $fails = [
            'no parameter given' => '/setup/start.json',
            'only one parameter given' => '/setup/start/' . UuidFactory::uuid() . '.json',
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
    public function testSetupStartController_Error_BadRequest_InvalidParameters(): void
    {
        $user = UserFactory::make()->inactive()->persist();
        $fails = [
            'user not a uuid' => '/setup/start/nope/' . UuidFactory::uuid() . '.json',
            'token not a uuid' => '/setup/start/' . $user->id . '/nope.json',
            'both not a uuid' => '/setup/start/nope/nope.json',

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
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_UserAlreadyActive(): void
    {
        $token = UuidFactory::uuid();
        $userId = UserFactory::make()->active()->persist()->id;
        $url = "/setup/start/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_UserNotExist(): void
    {
        $token = UuidFactory::uuid();
        $userId = UuidFactory::uuid();
        $url = "/setup/start/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_UserDeleted(): void
    {
        $token = UuidFactory::uuid();
        $userId = UserFactory::make()->inactive()->deleted()->persist()->id;
        $url = "/setup/start/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_TokenDoesntExist(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $token = UuidFactory::uuid();
        $url = "/setup/start/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_WrongTokenType(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $url = "/setup/start/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_TokenAlreadyConsumed(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->inactive()
            ->persist();
        $url = "/setup/start/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartController_Error_BadRequest_TokenExpired(): void
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->expired()
            ->persist();
        $url = "/setup/start/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'token');
        $this->assertNotNull($error, 'The test should return an error for the given field.');
        $this->assertEquals('The token is expired.', $error['expired']);
    }
}
