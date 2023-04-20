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
 * @since         3.3.0
 */
namespace App\Middleware;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Http\ServerRequest;

trait UacAwareMiddlewareTrait
{
    /**
     * Read in the authentication service the authenticated user.
     * All Middleware using this triat should be loaded after the
     * AuthenticationMiddleware
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return \App\Utility\UserAccessControl User Access Controller (GUEST) if not authenticated
     * @see Application::middleware()
     */
    protected function getUacInRequest(ServerRequest $request): UserAccessControl
    {
        // Return false if user is not logged in
        /** @var \Authentication\Identity $identity */
        $identity = $request->getAttribute('identity');
        if (empty($identity)) {
            return new UserAccessControl(Role::GUEST);
        }

        // User might be stored in the field "user" of the
        // identity (JWT Login endpoint) or can directly be
        // the identity itself (general way)
        $user = $identity->get('user') ?? $identity;

        return new UserAccessControl($user['role']['name'], $user['id'], $user['username']);
    }
}
