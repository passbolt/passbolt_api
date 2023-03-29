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
 * @since         2.0.0
 */
namespace PassboltTestData\Command\Base;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Console\ConsoleIo;
use Cake\ORM\Locator\LocatorInterface;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use PassboltTestData\Lib\DataCommand;

class FoldersRelationsDataCommand extends DataCommand
{
    public $entityName = 'FoldersRelations';

    /**
     * Get the groups users association data
     *
     * @return array
     */
    public function getData()
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $foldersRelations = $this->_getData();

        // Additional metadata
        foreach ($foldersRelations as $i => $row) {
            if (!array_key_exists('folder_parent_id', $row)) {
                $foldersRelations[$i]['folder_parent_id'] = null;
            }
            if (!array_key_exists('created', $row)) {
                $foldersRelations[$i]['created'] = '2020-02-01 00:00:00';
            }
            if (!array_key_exists('modified', $row)) {
                $foldersRelations[$i]['modified'] = '2020-02-01 00:00:00';
            }
        }

        return $foldersRelations;
    }

    /**
     * Get groups users settings
     *
     * @return array
     */
    protected function _getData()
    {
        // Admin Folders
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.bank'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.credit-cards'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.vat'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.communication'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.blogs'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.social-networks'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.social-networks'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.human-resources'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.it'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.certificates'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.continuous-integration'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.licenses'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.private-admin'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.production'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.staging'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.marketing'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.sales'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.travel'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.admin'),
        ];

        // Ada folders relations
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.bank'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.credit-cards'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.vat'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.communication'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.blogs'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.social-networks'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.social-networks'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.communication'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.human-resources'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.it'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.certificates'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.continuous-integration'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.licenses'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.private-ada'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.production'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.staging'),
            'folder_parent_id' => UuidFactory::uuid('folder.id.it'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.marketing'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.sales'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];
        $foldersRelations[] = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => UuidFactory::uuid('folder.id.travel'),
            'folder_parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.ada'),
        ];

        return $foldersRelations;
    }
}
