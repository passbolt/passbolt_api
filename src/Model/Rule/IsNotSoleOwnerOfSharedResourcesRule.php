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
 * @since         2.0.0
 */

namespace App\Model\Rule;

use App\Model\Table\PermissionsTable;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class IsNotSoleOwnerOfSharedResourcesRule
{
    /**
     * Performs the check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        /** @var \App\Model\Table\PermissionsTable $Permissions */
        $Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $checkGroupsUsers = false;

        // Check also the groups the aro is member of, if the aro is a User.
        if (is_a($entity, 'App\Model\Entity\User')) {
            $checkGroupsUsers = true;
        }

        $check = $Permissions
            ->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $entity->get('id'), [
                'checkGroupsUsers' => $checkGroupsUsers,
            ])
            ->count();

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $check += $Permissions
                ->findSharedAcosByAroIsSoleOwner(
                    PermissionsTable::FOLDER_ACO,
                    $entity->get('id'),
                    ['checkGroupsUsers' => $checkGroupsUsers]
                )->count();
        }

        return $check === 0;
    }
}
