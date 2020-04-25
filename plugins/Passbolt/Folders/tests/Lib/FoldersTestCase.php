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
namespace Passbolt\Folders\Test\Lib;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\EventListener\AddFolderParentIdBehavior;
use Passbolt\Folders\EventListener\PermissionsModelInitializeEventListener;
use Passbolt\Folders\EventListener\ResourcesEventListener;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;

abstract class FoldersTestCase extends AppTestCase
{
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders.enabled', true);

        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resourcesTable->addBehavior(ContainFolderParentIdBehavior::class);

        EventManager::instance()
            ->on(new ResourcesEventListener()) // Add folder relation when resource is created / update
            ->on(new AddFolderParentIdBehavior()) // Decorate the query to add the "folder_parent_id" property on the entities
            ->on(new PermissionsModelInitializeEventListener()); // Decorate the permissions table class to add cleanup method
    }
}
