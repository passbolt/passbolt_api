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
 * @since         3.3.2
 */

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionAuthPreventDeletedOrDisabledUsersMiddleware implements MiddlewareInterface
{
    /**
     * Destroys the session if an authenticated user is in the session
     * and this user is soft deleted.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        $userId = $request->getSession()->read('Auth.user.id');
        if (is_string($userId) && $this->isUserDeletedOrDisabled($userId)) {
            $request->getSession()->destroy();
        }

        return $handler->handle($request);
    }

    /**
     * Returns true if the user with the provided userId
     * is deleted or disabled.
     *
     * @param string $userId user ID
     * @return bool
     */
    public function isUserDeletedOrDisabled(string $userId): bool
    {
        try {
            /** @var \App\Model\Entity\User $user */
            $user = TableRegistry::getTableLocator()->get('Users')
                ->find()
                ->where(['Users.id' => $userId])
                ->firstOrFail();
        } catch (\Exception $exception) {
            // Not found => hard deleted
            return true;
        }

        return $user->isDeleted() || $user->isDisabled();
    }
}
