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

use App\Application;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestCase;
use App\Utility\Common;

/**
 * ApplicationTest class
 */
class ApplicationTest extends IntegrationTestCase
{
    /**
     * The response for the most recent json request.
     *
     * @var \Cake\Http\Response|null
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
        // @todo throw exception if empty json
        if (!empty($this->_responseJson)) {
            $this->_responseJsonHeader = $this->_responseJson->header;
            $this->_responseJsonBody = $this->_responseJson->body;
        }
    }

    /**
     * Asserts that the json response status code is a success.
     *
     * @return void
     */
    public function assertSuccess()
    {
        $this->assertResponseOk();
        $this->assertEquals('success', $this->_responseJsonHeader->status, 'Request should be a success');
    }

    /**
     * Asserts that the json response status code is an error.
     *
     * @return void
     */
    public function assertError()
    {
        $this->assertEquals('error', $this->_responseJsonHeader->status, 'Request should be an error');
    }

    /**
     * Set session.
     *
     * @param string $userFirstName The user first name.
     */
    public function authenticateAs($userFirstName) {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => Common::uuid('user.id.' . $userFirstName),
                ]
            ]
        ]);
    }

    /**
     * testMiddleware
     *
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->get(0));
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->get(1));
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->get(2));
    }
}
