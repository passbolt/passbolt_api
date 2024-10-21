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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Metadata\Model\Rule\IsFolderV5ToV4DowngradeAllowedRule;

class MetadataFolderUpdateListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return ['Model.beforeSave' => 'updateFolderMetadata'];
    }

    /**
     * Updates metadata fields if folder is being downgraded from V5 to V6.
     *
     * @param \Cake\Event\EventInterface $event Event object.
     * @param \Cake\ORM\Entity $entity Resource being updated.
     * @return void
     */
    public function updateFolderMetadata(EventInterface $event, Entity $entity): void
    {
        if (!$event->getSubject() instanceof FoldersTable) {
            return;
        }

        $isNameDirty = $entity->isDirty('name');
        $isFolderDowngradeToV4 = IsFolderV5ToV4DowngradeAllowedRule::isFolderDowngradeToV4(
            $entity->getOriginal('name'),
            $entity->get('name')
        );
        if ($isNameDirty && $isFolderDowngradeToV4) {
            // update entity to set metadata fields to null
            $entity->set('metadata', null);
            $entity->set('metadata_key_id', null);
            $entity->set('metadata_key_type', null);
        }
    }
}
