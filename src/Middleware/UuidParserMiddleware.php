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
 * @since         4.2.0
 */
namespace App\Middleware;

use Cake\Http\ServerRequest;
use Cake\Validation\Validation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UuidParserMiddleware implements MiddlewareInterface
{
    private ServerRequest $request;

    /**
     * Lowers UUIDs passed in the URL
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\BadRequestException if the API version provided is deprecated
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $this->request = $request;
        $this->lowerCaseUuidInPass();
        $this->lowerCaseUuidInQuery();

        return $handler->handle($this->request);
    }

    /**
     * @return \Cake\Http\ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * Lower all Uuids passed in the URL
     *
     * @return void
     */
    protected function lowerCaseUuidInPass(): void
    {
        $this->lowerUuidsInRequestParam('pass');
    }

    /**
     * Lower all Uuids passed in the Query
     *
     * @return void
     */
    protected function lowerCaseUuidInQuery(): void
    {
        $this->lowerUuidsInRequestParam('query');
    }

    /**
     * Lowers all the valid UUIDs passed in the request
     *
     * @param string $param Parameter in the query to modify ('pass' or 'query')
     * @return void
     */
    protected function lowerUuidsInRequestParam(string $param): void
    {
        $paramsInRequest = $this->request->getParam($param);
        if (!is_array($paramsInRequest) || empty($param)) {
            return;
        }

        $hasUuid = false;
        foreach ($paramsInRequest as $k => $v) {
            if (Validation::uuid($v)) {
                $hasUuid = true;
                $paramsInRequest[$k] = strtolower($v);
            }
        }
        if (!$hasUuid) {
            return;
        }

        $this->request = $this->request->withParam($param, $paramsInRequest);
    }
}
