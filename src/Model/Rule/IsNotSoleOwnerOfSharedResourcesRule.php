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

use App\Model\Entity\Group;
use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class IsNotSoleOwnerOfSharedResourcesRule
{
    /**
     * Performs the check
     *
     * @param \App\Model\Entity\Group|\App\Model\Entity\User $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Group|User $entity, array $options = []): bool
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
            ->innerJoinWith('Resources', function (Query $q) {
                return $q->where(['Resources.deleted' => false])
                    // Filter out resources with deleted resource types
                    ->innerJoinWith('ResourceTypes', function ($q) {
                        return $q->where([$q->expr()->isNull('ResourceTypes.deleted')]);
                    });
            })
            ->limit(1)
            ->all()
            ->count();

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $check += $Permissions
                ->findSharedAcosByAroIsSoleOwner(
                    PermissionsTable::FOLDER_ACO,
                    $entity->get('id'),
                    ['checkGroupsUsers' => $checkGroupsUsers]
                )->limit(1)->all()->count();
        }

        return $check === 0;
    }
}
