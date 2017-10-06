<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase;

use Cake\TestSuite\IntegrationTestCase;
use App\Utility\Common;

class ApplicationTest extends IntegrationTestCase
{
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
     * Asserts that the json response status code is an error.
     *
     * @return void
     */
    public function assertAuthenticationError()
    {
        $this->assertError(403, 'You need to login to access this location.');
    }

    /**
     * Asserts that an object has specified attributes.
     *
     * @param string $attributesNames
     * @param object $object
     */
    public function assertObjectHasAttributes($attributesNames, $object)
    {
        foreach ($attributesNames as $attributeName) {
            $this->assertObjectHasAttribute($attributeName, $object);
        }
    }

    /**
     * Asserts that an object has specified attributes.
     *
     * @param string $attributesNames
     * @param object $object
     */
    public function assertArrayHasAttributes($attributesNames, $check)
    {
        foreach ($attributesNames as $attributeName) {
            $this->assertTrue(
                isset($check[$attributeName]),
                'The following attribute is missing in array: ' . $attributeName
            );
        }
    }

    /**
     * Asserts that an object has all the attributes a resource should have.
     *
     * @param object $resource
     */
    protected function assertResourceAttributes($resource)
    {
        $attributes = ['id', 'name', 'username', 'uri', 'description', 'deleted', 'created', 'modified', 'created_by', 'modified_by'];
        $this->assertObjectHasAttributes($attributes, $resource);
    }

    /**
     * Asserts that an object has all the attributes a secret should have.
     *
     * @param object $secret
     */
    protected function assertSecretAttributes($secret)
    {
        $attributes = ['id', 'user_id', 'resource_id', 'data', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $secret);
    }

    /**
     * Asserts that an object has all the attributes a user should have.
     *
     * @param object $user
     */
    protected function assertUserAttributes($user)
    {
        $attributes = ['id', 'role_id', 'username', 'active', 'deleted', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $user);
    }

    /**
     * Asserts that an object has all the attributes a favorite should have.
     *
     * @param object $favorite
     */
    protected function assertFavoriteAttributes($favorite)
    {
        $attributes = ['id', 'user_id', 'foreign_id', 'foreign_model', 'created'];
        $this->assertObjectHasAttributes($attributes, $favorite);
    }

    /**
     * Authenticate as a user.
     *
     * @param string $userFirstName The user first name.
     * @return void
     */
    public function authenticateAs($userFirstName)
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => Common::uuid('user.id.' . $userFirstName),
                ]
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
