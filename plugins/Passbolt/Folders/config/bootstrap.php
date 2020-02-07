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
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Passbolt\Folders\EventListener\AddFolderParentIdBehavior;
use Passbolt\Folders\EventListener\ResourceEventListener;
use Passbolt\Folders\Notification\Email\FoldersEmailRedactorPool;

Configure::load('Passbolt/Folders.config', 'default', true);

EventManager::instance()
    ->on(new ResourceEventListener()) // Add folder relation when resource is created
    ->on(new AddFolderParentIdBehavior()) // Decorate the query to add the "folder_parent_id" property on the entities
    ->on(new FoldersEmailRedactorPool()); // Register email redactors
