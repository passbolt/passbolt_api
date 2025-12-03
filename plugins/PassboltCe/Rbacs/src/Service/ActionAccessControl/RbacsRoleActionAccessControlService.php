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

namespace Passbolt\Rbacs\Service\ActionAccessControl;

use App\Model\Entity\Role;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;

/**
 * Checks that the action is authorized for the authentified user
 */
class RbacsRoleActionAccessControlService implements RoleActionAccessControlServiceInterface
{
    /**
     * @inheritDoc
     */
    public function controlUserRoleActionAccess(Role $role, string $actionId): void
    {
        if ($role->isAdmin()) {
            return;
        }

        /** @var \Passbolt\Rbacs\Model\Table\RbacsTable $RbacsTable */
        $RbacsTable = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
        if ($RbacsTable->isActionAllowedForRole($role->id, $actionId)) {
            return;
        }

        throw new ForbiddenException(__('You are not authorized to access that location.'));
    }
}
