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
namespace App\Test\Lib\Utility;
use PHPUnit\Framework\Assert;

trait JsonRequestTrait
{
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
     * @param array $data The data for the request.
     * @return void
     */
    public function deleteJson($url, $data = [])
    {
        $this->_sendRequest($url, 'DELETE', $data);
        $this->_responseJson = json_decode($this->_getBodyAsString());
        if (empty($this->_responseJson)) {
            Assert::fail('The result of the request is not a valid json.');
        }
        $this->_responseJsonHeader = $this->_responseJson->header;
        $this->_responseJsonBody = $this->_responseJson->body;
    }
}