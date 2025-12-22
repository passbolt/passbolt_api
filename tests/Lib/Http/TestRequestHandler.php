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
 * @since         4.5.0
 */
namespace App\Test\Lib\Http;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Helper class to pass to middleware as a request handler.
 *
 * Inspired from:
 *
 * @link https://github.com/cakephp/cakephp/blob/5.x/tests/test_app/TestApp/Http/TestRequestHandler.php
 */
class TestRequestHandler implements RequestHandlerInterface
{
    public $callable;

    protected ServerRequestInterface $request;

    public function __construct(?callable $callable = null)
    {
        $this->callable = $callable ?: function ($request) {
            return new Response();
        };
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        return ($this->callable)($this->request);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
