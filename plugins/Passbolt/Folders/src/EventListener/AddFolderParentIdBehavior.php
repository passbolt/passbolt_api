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

namespace Passbolt\Folders\EventListener;

use App\Model\Table\ResourcesTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Table;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;

/**
 * Add the FolderParentId behavior at runtime. It allows to add a behavior on core classes without directly changing the
 * decorated class.
 *
 * Class AddFolderParentIdBehavior
 * @package Passbolt\Folders\EventListener
 */
class AddFolderParentIdBehavior implements EventListenerInterface
{
    const TABLES_TO_ADD = [
        ResourcesTable::class,
    ];

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Model.initialize' => $this,
        ];
    }

    /**
     * @param EventInterface $event Event
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        foreach (static::TABLES_TO_ADD as $allowed) {
            if ($event->getSubject() instanceof $allowed) {
                /** @var Table $table */
                $table = $event->getSubject();
                $table->addBehavior(ContainFolderParentIdBehavior::class);

                return;
            }
        }
    }
}
