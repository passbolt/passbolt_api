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
 * @since         5.5.0
 */
namespace Passbolt\Metadata\Event;

use App\Middleware\UacAwareMiddlewareTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use JsonException;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Service\MetadataKeysSettingsSetService;

class FlushServerKeyOnMetadataKeysSettingsUpdateListener implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            MetadataKeysSettingsSetService::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME => 'flushMetadataServerKey',
        ];
    }

    /**
     * Remove all the server key entries from metadata private keys table when Zero-Knowledge mode is enabled.
     *
     * @param \App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal $event The event.
     * @return void
     */
    public function flushMetadataServerKey(EventInterface $event): void
    {
        /** @var \App\Model\Entity\OrganizationSetting $entity */
        $entity = $event->getData('updatedEntity');
        if (empty($entity->value)) {
            return;
        }

        try {
            $data = json_decode($entity->value, true, 2, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            // Gracefully return if unable to decode the settings value
            return;
        }

        if ($entity->isNew() || !$data[MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE]) {
            return;
        }

        /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $table */
        $table = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataPrivateKeys');
        /** @var \Cake\ORM\Query\SelectQuery $query */
        $query = $table->find();
        $serverPrivateKeys = $query->where([$query->newExpr()->isNull('user_id')])->all();

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $serverPrivateKey */
        foreach ($serverPrivateKeys as $serverPrivateKey) {
            $result = $table->delete($serverPrivateKey);
            if (!$result) {
                Log::error("Unable to flush metadata private key: {$serverPrivateKey->id}");
            }
        }
    }
}
