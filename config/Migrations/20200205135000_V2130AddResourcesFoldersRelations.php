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
 * @since         2.13.0
 */

use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class V2130AddResourcesFoldersRelations extends AbstractMigration
{
    public function up()
    {
        $getUsersIdsHavingAccessToServices = new PermissionsGetUsersIdsHavingAccessToService();
        $stmt = $this->query("SELECT id FROM resources WHERE deleted=false");
        $rows = $stmt->fetchAll(\PDO::FETCH_BOTH);
        foreach($rows as $row) {
            $usersIdsHavingAccessTo = $getUsersIdsHavingAccessToServices->getUsersIdsHavingAccessTo($row['id']);
            $this->addResourceFoldersRelations($row['id'], $usersIdsHavingAccessTo);
        }
    }

    private function addResourceFoldersRelations(string $resourceId, array $usersIdsHavingAccessTo)
    {
        $foldersRelationsTable = $this->table('folders_relations');
        $data = [];

        foreach ($usersIdsHavingAccessTo as $userId) {
            $data[] = [
                'id' => UuidFactory::uuid(),
                'foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE,
                'foreign_id' => $resourceId,
                'user_id' => $userId,
                'folder_parent_id' => null,
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ];
        }

        $foldersRelationsTable->insert($data)->saveData();
    }
}
