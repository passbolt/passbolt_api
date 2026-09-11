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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditionDowngradeDisabledMiddleware implements MiddlewareInterface
{
    public const PASSBOLT_SECURITY_EDITION_DOWNGRADE_DISABLED = 'passbolt.security.edition.downgradeDisabled';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     * @throws \Cake\Http\Exception\ForbiddenException When the downgrade is disabled.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (Configure::read(self::PASSBOLT_SECURITY_EDITION_DOWNGRADE_DISABLED)) {
            throw new ForbiddenException(__('Edition downgrade is disabled.'));
        }

        return $handler->handle($request);
    }
}
