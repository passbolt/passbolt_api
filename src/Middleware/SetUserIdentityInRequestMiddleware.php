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
 * @since         5.8.0
 */
namespace App\Middleware;

use App\Model\Entity\User;
use Authentication\Identity;
use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetUserIdentityInRequestMiddleware implements MiddlewareInterface
{
    /**
     * Overwrites the identity in the request with the user entity.
     * This is needed on session authentication, where only the user ID is stored in the session
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\BadRequestException if the API version provided is deprecated
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Authentication\Identity $identity */
        $identity = $request->getAttribute('identity');
        // In Jwt Authentication, the complete user is already stored in the identity
        if ($identity instanceof User) {
            return $handler->handle($request);
        }
        $identity = $this->getAuthIdentifier($identity);
        $request = $request->withAttribute('identity', $identity);

        return $handler->handle($request);
    }

    /**
     * Get an identifier with the username and role
     *
     * @param ?\Authentication\Identity $identity Identity set in authentication middleware.
     * @return ?\App\Model\Entity\User User entity.
     */
    private function getAuthIdentifier(?Identity $identity): ?User
    {
        // If the user is not authenticated, return null
        if (empty($identity)) {
            return null;
        }

        // At this stage the identity is an array with the user id
        // As an additional check, we check if the user id is set.
        $userId = $identity->get('user')['id'] ?? null;
        if (empty($userId)) {
            return null;
        }

        return TableRegistry::getTableLocator()->get('Users')
            ->find('authIdentifier')
            ->where(['Users.id' => $userId])
            ->first();
    }
}
