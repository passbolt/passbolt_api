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

use App\Model\Entity\Permission;
use Cake\ORM\TableRegistry;

class PermissionsAcoHasOwnerService
{
    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Check that an aco has an owner permission.
     *
     * @param string $acoForeignKey The permission aco instance id
     * @return bool
     * @throws \Exception
     */
    public function check(string $acoForeignKey): bool
    {
        return $this->permissionsTable->findByAcoForeignKeyAndType($acoForeignKey, Permission::OWNER)
            ->count() > 0;
    }
}
