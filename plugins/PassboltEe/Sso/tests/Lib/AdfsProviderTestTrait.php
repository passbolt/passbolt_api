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
 * @since         4.6.0
 */
namespace Passbolt\Sso\Test\Lib;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

trait AdfsProviderTestTrait
{
    /**
     * @param int $statusCode Status code.
     * @param array $headers Headers.
     * @param mixed $body Set response body.
     * @return Client
     * @link https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function mockHttpClientResponse(int $statusCode, array $headers = [], $body = null): Client
    {
        $mockHandler = new MockHandler([new Response($statusCode, $headers, $body)]);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client(['handler' => $handlerStack]);
    }
}
