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
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Test\Lib\Utility\ArrayTrait;
use App\Test\Lib\Utility\ObjectTrait;
use App\Utility\Common;
use Cake\TestSuite\IntegrationTestCase;

class AppIntegrationTestCase extends IntegrationTestCase
{
    use FavoritesModelTrait;
    use GroupsModelTrait;
    use PermissionsModelTrait;
    use ProfilesModelTrait;
    use ResourcesModelTrait;
    use SecretsModelTrait;
    use UsersModelTrait;

    use ArrayTrait;
    use ObjectTrait;

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
     * @return void
     */
    public function assertError($code = null, $message = '')
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
            $this->assertRegExp("/$message/", $this->_responseJsonHeader->message);
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
    public function assertForbiddenError()
    {
        $this->assertError(403, 'Forbidden.');
    }

    /**
     * Authenticate as a user.
     *
     * @param string $userFirstName The user first name.
     * @param string $roleName (optional) The role name, by default guest.
     * @return void
     */
    public function authenticateAs($userFirstName, $roleName = Role::GUEST)
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => Common::uuid('user.id.' . $userFirstName),
                    'Role' => [
                        'name' => $roleName
                    ]
                ],
            ]
        ]);
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
     * @throws \Exception when the result of the request is not a valid json.
     */
    public function getJson($url)
    {
        $this->get($url);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            throw new \Exception('The result of the request is not a valid json.');
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
     * @throws \Exception when the result of the request is not a valid json.
     */
    public function postJson($url, $data = [])
    {
        $this->post($url, $data);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            throw new \Exception('The result of the request is not a valid json.');
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
     * @throws \Exception when the result of the request is not a valid json.
     */
    public function deleteJson($url)
    {
        $this->delete($url);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            throw new \Exception('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }
}
