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

use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use Cake\ORM\TableRegistry;

class IsMetadataKeyTypeSharedOnSharedResourceRule
{
    /**
     * Performs the check
     *
     * Checks that if the `metadata_key_type` is 'user_key', the resource is not shared with
     * other users, or a group
     *
     * @param \App\Model\Entity\Resource $resource The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Resource $resource, array $options): bool
    {
        // If the resource's metadata key type is not personal, the present rule does not apply
        $isPersonal = $resource->metadata_key_type === 'user_key';
        if (!$isPersonal) {
            return true;
        }

        /** @var \App\Model\Table\PermissionsTable $PermissionsTable */
        $PermissionsTable = TableRegistry::getTableLocator()->get('Permissions');

        $permissions = $PermissionsTable->find()
            ->where(['aco_foreign_key' => $resource->get('id')])
            ->limit(2)->all();

        // If the resource has more than one permission, the metadata key type should not be "user_key"
        if ($permissions->count() > 1) {
            return false;
        }

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $permissions->first();
        if (is_null($permission)) {
            return false;
        }

        if ($permission->aro === PermissionsTable::GROUP_ARO) {
            return false;
        }

        return true;
    }
}
