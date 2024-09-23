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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use App\Model\Table\PermissionsTable;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class IsMetadataKeyTypeSharedOnSharedItemRule
{
    /**
     * Performs the check
     *
     * Checks that if the `metadata_key_type` is 'user_key', the entity is not shared with
     * other users, or a group
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        // If the resource's metadata key type is not personal, the present rule does not apply
        $isPersonal = $entity->get('metadata_key_type') === 'user_key';
        if (!$isPersonal) {
            return true;
        }

        /** @var \App\Model\Table\PermissionsTable $PermissionsTable */
        $PermissionsTable = TableRegistry::getTableLocator()->get('Permissions');

        $permissions = $PermissionsTable->find()
            ->where(['aco_foreign_key' => $entity->get('id')])
            ->limit(2)
            ->all();

        // If the resource has more than one permission, the metadata key type should not be "user_key"
        if ($permissions->count() > 1) {
            return false;
        }

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $permissions->first();
        if (is_null($permission)) {
            if (Configure::read('debug')) {
                Log::error(__('No permission found for the entity: {0}', $entity->get('id')));
            }

            return false;
        }

        if ($permission->aro === PermissionsTable::GROUP_ARO) {
            return false;
        }

        return true;
    }
}
