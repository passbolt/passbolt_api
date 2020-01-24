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
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;

class FoldersRelationsDeleteService
{
    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    public function delete(UserAccessControl $uac, string $foreignId)
    {
        $this->foldersRelationsTable->getConnection()->transactional(function () use ($uac, $foreignId) {
            $this->deleteFolderRelation($uac, $foreignId);
        });
    }

    private function deleteFolderRelation($uac, $foreignId)
    {
        $this->foldersRelationsTable->deleteAll([
            'foreign_id' => $foreignId,
            'user_id' => $uac->userId(),
        ]);
    }
}
