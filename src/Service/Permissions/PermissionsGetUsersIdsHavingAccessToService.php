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
 * @since         2.13.0
 */

namespace App\Service\Permissions;

use App\Model\Table\PermissionsTable;
use Cake\ORM\Locator\LocatorAwareTrait;

class PermissionsGetUsersIdsHavingAccessToService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $GroupsUsers;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $Permissions;

    /**
     * Instantiate the service
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        /** @phpstan-ignore-next-line */
        $this->Permissions = $this->fetchTable('Permissions');
    }

    /**
     * Get the list of users ids having access to a given aco.
     *
     * @param string $acoForeignKey The aco to look for
     * @return array
     */
    public function getUsersIdsHavingAccessTo(string $acoForeignKey)
    {
        // Retrieve the groups having access to the aco.
        $groupsIdsHavingAccessQuery = $this->Permissions
            ->findByAroAndAcoForeignKey(PermissionsTable::GROUP_ARO, $acoForeignKey)
            ->select('aro_foreign_key');

        // Retrieve the groups members having access to the aco.
        $groupUsersIds = $this->GroupsUsers->find()
            ->where(['group_id IN' => $groupsIdsHavingAccessQuery])
            ->select('user_id')
            ->all()
            ->extract('user_id')
            ->toArray();

        // Retrieve the users having access to the aco.
        $usersIds = $this->Permissions
            ->findByAroAndAcoForeignKey(PermissionsTable::USER_ARO, $acoForeignKey)
            ->select('aro_foreign_key')
            ->all()
            ->extract('aro_foreign_key')
            ->toArray();

        return array_unique(array_merge($groupUsersIds, $usersIds));
    }
}
