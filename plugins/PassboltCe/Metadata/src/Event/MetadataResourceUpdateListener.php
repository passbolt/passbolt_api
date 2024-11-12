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

use App\Model\Table\ResourcesTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Passbolt\Metadata\Model\Rule\IsResourceV5ToV4DowngradeAllowedRule;

class MetadataResourceUpdateListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return ['Model.beforeSave' => 'updateResourceMetadata'];
    }

    /**
     * Updates metadata fields if resource is being downgrade from V5 to V6.
     *
     * @param \Cake\Event\EventInterface $event Event object.
     * @param \Cake\ORM\Entity $entity Resource being updated.
     * @return void
     */
    public function updateResourceMetadata(EventInterface $event, Entity $entity): void
    {
        if (!$event->getSubject() instanceof ResourcesTable) {
            return;
        }

        $isMetadataTypeDirty = $entity->isDirty('resource_type_id');
        $isResourceTypeDowngrade = IsResourceV5ToV4DowngradeAllowedRule::isResourceTypeChangeToV4(
            $entity->getOriginal('resource_type_id'),
            $entity->get('resource_type_id')
        );
        if ($isMetadataTypeDirty && $isResourceTypeDowngrade) {
            // update entity to set metadata fields to null
            $entity->set('metadata', null);
            $entity->set('metadata_key_id', null);
            $entity->set('metadata_key_type', null);
        }
    }
}
