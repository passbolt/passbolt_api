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
 * @since         5.15.0
 */

namespace Passbolt\Sso\Utility\Http;

use Cake\Core\Configure;
use Cake\Log\Log;
use Passbolt\Sso\Error\Exception\SsoEgressBlockedException;
use Psr\Http\Message\RequestInterface;

/**
 * Guzzle middleware that validates the destination of the SSO provider request and every redirect
 * hop against the egress guard before it is dispatched.
 */
class SsoEgressGuardMiddleware
{
    public const CONFIG_BLOCK = 'passbolt.security.sso.egress.block';

    /**
     * Guzzle middleware entry point: wraps the next handler with the egress check.
     *
     * @param callable $handler Next handler in the stack.
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $this->assertAllowed($request);

            return $handler($request, $options);
        };
    }

    /**
     * Block the request (or warn) when its destination is not allowed by the egress guard.
     *
     * @param \Psr\Http\Message\RequestInterface $request The outgoing request.
     * @return void
     * @throws \Passbolt\Sso\Error\Exception\SsoEgressBlockedException When blocking is enabled and the host is blocked.
     */
    private function assertAllowed(RequestInterface $request): void
    {
        $host = $request->getUri()->getHost();
        $reason = (new SsoEgressGuard())->getBlockReason($host);
        if ($reason === null) {
            return;
        }

        if ((bool)Configure::read(self::CONFIG_BLOCK, false)) {
            Log::error('SSO egress guard blocked a request. ' . $reason);
            throw new SsoEgressBlockedException(
                __('Single sign-on failed.') . ' ' . __('The provider address is not allowed.'),
            );
        }

        // Warn-only mode: log and let the request through.
        Log::warning('SSO egress guard (warn-only) flagged a request. ' . $reason);
    }
}
