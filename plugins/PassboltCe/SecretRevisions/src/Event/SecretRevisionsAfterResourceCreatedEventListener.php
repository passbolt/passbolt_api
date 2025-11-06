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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Event;

use App\Model\Entity\Resource;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Passbolt\SecretRevisions\Service\CreateSecretRevisionsService;

class SecretRevisionsAfterResourceCreatedEventListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.beforeSave' => 'createNewRevision', // when a resource's secret is updated
            'Model.afterSave' => 'createFirstRevision', // when a resource is created
        ];
    }

    /**
     * When a resource has been created, create its first revision and update the secrets accordingly
     *
     * @param \Cake\Event\Event $event a user has completed registration
     * @param \Cake\Datasource\EntityInterface $resource the resource being saved
     * @return void
     */
    public function createFirstRevision(Event $event, EntityInterface $resource): void
    {
        if (!($resource instanceof Resource)) {
            return;
        }
        // For now use the listener only on resource creation
        if (!$resource->isNew()) {
            return;
        }

        (new CreateSecretRevisionsService())->createFirstRevision($resource);
    }

    /**
     * Create a new revision:
     * - when a resource's secrets are being updated
     *
     * @param \Cake\Event\Event $event a user has completed registration
     * @param \Cake\Datasource\EntityInterface $resource the resource being saved
     * @return void
     */
    public function createNewRevision(Event $event, EntityInterface $resource): void
    {
        if (!($resource instanceof Resource)) {
            return;
        }
        if ($resource->isNew()) {
            return;
        }

        (new CreateSecretRevisionsService())->createNewRevision($resource);
    }
}
