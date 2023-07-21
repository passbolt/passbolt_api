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
 * @since         3.8.0
 */
namespace Passbolt\Folders\Test\Factory;

use App\Model\Table\PermissionsTable;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Model\Entity\Folder;

/**
 * PermissionFactory
 *
 * @method \App\Model\Entity\Permission|\App\Model\Entity\Permission[] persist()
 * @method \App\Model\Entity\Permission getEntity()
 * @method \App\Model\Entity\Permission[] getEntities()
 */
class PermissionFactory extends \App\Test\Factory\PermissionFactory
{
    public function initialize(): void
    {
        parent::initialize();
        FoldersPlugin::addAssociationsToPermissionsTable($this->getTable());
    }

    /**
     * Define the associated folder aco
     *
     * @param FolderFactory|null $factory
     * @return PermissionFactory
     */
    public function withAcoFolder(?FolderFactory $factory = null): self
    {
        $this->patchData(['aco' => PermissionsTable::FOLDER_ACO]);

        return $this->with('Folders', $factory);
    }

    /**
     * Define the aro as group
     *
     * @param Folder|null $folder (optional) Folder to use as aco_foregin_key
     * @return PermissionFactory
     */
    public function acoFolder(?Folder $folder = null): self
    {
        $this->patchData(['aco' => PermissionsTable::FOLDER_ACO]);

        if (!is_null($folder)) {
            $this->patchData(['aco_foreign_key' => $folder->id]);
        }

        return $this;
    }
}
