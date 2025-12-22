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
 * @since         5.4.0
 */

namespace App\Model\Rule\User;

use App\Model\Entity\Role;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class IsNotGuestRoleIdRule
{
    /**
     * Performs the check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return string|bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool|string
    {
        $roleId = $entity['role_id'] ?? null;
        if (!is_string($roleId)) {
            return false;
        }
        $roleId = strtolower($roleId);
        /** @var \App\Model\Table\RolesTable $RolesTable */
        $RolesTable = TableRegistry::getTableLocator()->get('Roles');

        $roleIdIsGuest = $RolesTable->exists([
                'id' => $roleId,
                'name' => Role::GUEST,
            ]);

        if ($roleIdIsGuest) {
            return __('The user role ID must not be of the guest role.');
        }

        return true;
    }
}
