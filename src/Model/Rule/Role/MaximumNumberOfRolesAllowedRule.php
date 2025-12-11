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

namespace App\Model\Rule\Role;

use App\Model\Table\RolesTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class MaximumNumberOfRolesAllowedRule
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $query = $rolesTable->find();
        $countActiveRoles = $query
            ->where([$query->newExpr()->isNull($rolesTable->aliasField('deleted'))])
            ->count();

        return $countActiveRoles < RolesTable::MAXIMUM_NO_OF_ROLES_ALLOWED;
    }
}
