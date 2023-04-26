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
    public function testSetupStart_NotFoundError_MissingUrlParameters()
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
    public function testSetupStartJson_NotFoundError_MissingUrlParameters()
    {
        $fails = [
            'no parameter given' => '/setup/start.json',
            'only one parameter given' => '/setup/start/' . UuidFactory::uuid() . '.json',
            'no parameter given on legacy url' => '/setup/install.json',
            'only one parameter given on legacy url' => '/setup/install/' . UuidFactory::uuid() . '.json',
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
    public function testSetupStart_BadRequestError_InvalidParameters()
    {
        $user = UserFactory::make()->inactive()->persist();
        $fails = [
            'user not a uuid' => '/setup/start/nope/' . UuidFactory::uuid() . '.json',
            'user not a uuid with legacy url' => '/setup/install/nope/' . UuidFactory::uuid() . '.json',
            'token not a uuid' => '/setup/start/' . $user->id . '/nope.json',
            'token not a uuid with legacy url' => '/setup/install/' . $user->id . '/nope.json',
            'both not a uuid' => '/setup/install/nope/nope.json',

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
    public function testSetupStartJson_BadRequestError_UserAlreadyActive()
    {
        $token = UuidFactory::uuid();
        $userId = UserFactory::make()->active()->persist()->id;
        $url = "/setup/install/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartJson_BadRequestError_UserNotExist()
    {
        $token = UuidFactory::uuid();
        $userId = UuidFactory::uuid();
        $url = "/setup/install/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartJson_BadRequestError_UserDeleted()
    {
        $token = UuidFactory::uuid();
        $userId = UserFactory::make()->inactive()->deleted()->persist()->id;
        $url = "/setup/install/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartJson_BadRequestError_TokenDoesntExist()
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $token = UuidFactory::uuid();
        $url = "/setup/install/{$userId}/{$token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartJson_BadRequestError_WrongTokenType()
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $url = "/setup/install/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartJson_BadRequestError_TokenAlreadyConsumed()
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->inactive()
            ->persist();
        $url = "/setup/install/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStart_BadRequestError_TokenExpired()
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->expired()
            ->persist();
        $url = "/setup/install/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseCode(400);
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'token');
        $this->assertNotNull($error, 'The test should return an error for the given field.');
        $this->assertEquals('The token is expired.', $error['expired']);
    }

    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testSetupStartSuccess()
    {
        $userId = UserFactory::make()->inactive()->persist()->id;
        $t = AuthenticationTokenFactory::make()
            ->userId($userId)
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->persist();
        $url = "/setup/install/{$userId}/{$t->token}.json";
        $this->getJson($url);
        $this->assertResponseOk();
        $this->assertNotNull($this->_responseJsonBody->user);
        $this->assertUserAttributes($this->_responseJsonBody->user);
    }
}
