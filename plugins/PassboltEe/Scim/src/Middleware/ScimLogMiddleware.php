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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Middleware;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Utility\Text;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Utility\ScimTools;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * ScimLogMiddleware class
 */
class ScimLogMiddleware implements MiddlewareInterface
{
    use FeaturePluginAwareTrait;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $logScimRequest = (
            Configure::read('passbolt.plugins.scim.logScimRequests', false) &&
            $this->isFeaturePluginEnabled('Scim') &&
            ScimTools::isScimApiRequest($request)
        );

        $requestId = Text::uuid();
        if ($logScimRequest) {
            ScimLog::info(
                sprintf(
                    '`%s` Request uri (%s): %s',
                    $requestId,
                    $request->getEnv('REQUEST_METHOD'),
                    $request->getUri()
                )
            );
            if ($request->is(['post', 'put', 'patch'])) {
                ScimLog::info(
                    sprintf(
                        "`%s` Request body: \n%s",
                        $requestId,
                        print_r($request->getParsedBody(), return: true)
                    )
                );
            }
        }

        $response = $handler->handle($request);
        if ($logScimRequest) {
            ScimLog::info(sprintf("`%s` Response body: \n%s", $requestId, $response->getBody()));
        }

        return $response;
    }
}
