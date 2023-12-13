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
 * @since         4.5.0
 */

namespace App\Service\Groups;

use App\Model\Entity\Group;

/**
 * Class ExpireResourcesOnGroupsUpdateServiceInterface.
 */
interface ExpireResourcesOnGroupsUpdateServiceInterface
{
    /**
     * When a user is removed from a group, mark the resources
     * they lost access for as expired, if these were ever accessed
     *
     * @param \App\Model\Entity\Group $group Group being updated
     * @param array $deletedGroupsUsers Array of GroupUsers being deleted
     * @return bool
     */
    public function expireResourcesIfUsersLostPermission(Group $group, array $deletedGroupsUsers): bool;
}
