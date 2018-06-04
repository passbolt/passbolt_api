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
namespace App\Test\Lib;

use App\Model\Entity\Role;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\RolesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Test\Lib\Utility\ArrayTrait;
use App\Test\Lib\Utility\EntityTrait;
use App\Test\Lib\Utility\ObjectTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;
use PHPUnit\Framework\Assert;

abstract class AppIntegrationTestCase extends IntegrationTestCase
{
    use ArrayTrait;
    use AvatarsModelTrait;
    use CommentsModelTrait;
    use EntityTrait;
    use FavoritesModelTrait;
    use GpgkeysModelTrait;
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use ObjectTrait;
    use PermissionsModelTrait;
    use ProfilesModelTrait;
    use ResourcesModelTrait;
    use RolesModelTrait;
    use SecretsModelTrait;
    use UsersModelTrait;

    /**
     * The response for the most recent json request.
     *
     * @var Object|array
     */
    protected $_responseJson;

    /**
     * The response header for the most recent json request.
     *
     * @var Object
     */
    protected $_responseJsonHeader;

    /**
     * The response body for the most recent json request.
     *
     * @var Object
     */
    protected $_responseJsonBody;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->initAvatarEvents();
        Configure::write('passbolt.plugins', []);
    }

    /**
     * Asserts that the latest json request is a success.
     *
     * @return void
     */
    public function assertSuccess()
    {
        $this->assertResponseOk();
        $this->assertEquals('success', $this->_responseJsonHeader->status, 'The request status should be a success.');
    }

    /**
     * Asserts that the latest json request failed.
     *
     * @param null $code (optional) Expected response code
     * @param string $message (optional) Expected response message.
     * @param string $errorMessage (optional) Test case error message to be displayed
     * @return void
     */
    public function assertError($code = null, $message = '', $errorMessage = null)
    {
        $this->assertEquals('error', $this->_responseJsonHeader->status, 'The request should be an error');

        // If expected response code given.
        if (!is_null($code)) {
            $this->assertResponseCode($code);
        } else {
            $this->assertResponseError();
        }

        // If message given.
        if (!empty($message)) {
            $this->assertRegExp("/$message/", $this->_responseJsonHeader->message, $errorMessage);
        }
    }

    /**
     * Asserts that the json response is relative to an authentication error.
     *
     * @return void
     */
    public function assertAuthenticationError()
    {
        $this->assertError(403, 'You need to login to access this location.');
    }

    /**
     * Asserts that the json response is relative to a forbidden error.
     *
     * @return void
     */
    public function assertForbiddenError($msg = 'Forbidden')
    {
        $this->assertError(403, $msg);
    }

    /**
     * Asserts that the json response is relative to a forbidden error.
     *
     * @return void
     */
    public function assertBadRequestError($msg = 'Bad Request')
    {
        $this->assertError(400, $msg);
    }

    /**
     * Authenticate as a user.
     *
     * @param string $userFirstName The user first name.
     * @return void
     */
    public function authenticateAs($userFirstName)
    {
        $data = [
            'id' => UuidFactory::uuid('user.id.' . $userFirstName),
            'username' => $userFirstName . '@passbolt.com',
            'profile' => [
                'first_name' => $userFirstName,
                'last_name' => 'testing',
            ],
            'role' => [
                'name' => Role::USER
            ]
        ];
        if ($userFirstName === 'admin') {
            $data['role']['name'] = Role::ADMIN;
        }
        $this->session(['Auth' => ['User' => $data]]);
    }

    /**
     * Performs a GET json request using the current request data.
     *
     * The response of the dispatched request will be stored as
     * a property (_responseJson). You can use various assert
     * methods to check the response.
     *
     * @param string|array $url The URL to request.
     * @return void
     */
    public function getJson($url)
    {
        $this->get($url);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            Assert::fail('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }

    /**
     * Performs a POST json request using the current request data.
     *
     * The response of the dispatched request will be stored as
     * a property (_responseJson). You can use various assert
     * methods to check the response.
     *
     * @param string|array $url The URL to request.
     * @param array $data The data for the request.
     * @return void
     */
    public function postJson($url, $data = [])
    {
        $this->post($url, $data);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            Assert::fail('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }

    /**
     * Performs a PUT json request using the current request data.
     *
     * The response of the dispatched request will be stored as
     * a property (_responseJson). You can use various assert
     * methods to check the response.
     *
     * @param string|array $url The URL to request.
     * @param array $data The data for the request.
     * @return void
     */
    public function putJson($url, $data = [])
    {
        $this->put($url, $data);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            Assert::fail('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }

    /**
     * Performs a DELETE json request using the current request data.
     *
     * The response of the dispatched request will be stored as
     * a property (_responseJson). You can use various assert
     * methods to check the response.
     *
     * @param string|array $url The URL to request.
     * @return void
     */
    public function deleteJson($url)
    {
        $this->delete($url);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            Assert::fail('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }
}
