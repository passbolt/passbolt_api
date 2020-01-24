<?php
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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Service;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;

class FoldersPermissionsDeleteService
{
    /**
     * @var PermissionsTable
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
     * Delete a folder permission
     *
     * @param UserAccessControl $uac The current user.
     * @param string $folderId The folder to create a permission for
     * @return void
     * @throws \Exception
     */
    public function delete(UserAccessControl $uac, string $folderId)
    {
        $this->permissionsTable->getConnection()->transactional(function () use ($uac, $folderId) {
            $this->deletePermission($uac, $folderId);
        });
    }

    /**
     * Delete the folder permission from the database.
     *
     * @param UserAccessControl $uac The current user.
     * @param string $folderId The folder to create a permission for
     * @return void
     */
    private function deletePermission(UserAccessControl $uac, string $folderId)
    {
        $this->permissionsTable->deleteAll([
            'aco_foreign_key' => $folderId,
            'aro_foreign_key' => $uac->userId(),
        ]);
    }
}
