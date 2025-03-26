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

use App\Model\Event\TableFindIndexBefore;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Query\SelectQuery;

/**
 * Listens to TableFindIndexBefore::EVENT_NAME event.
 */
class MetadataResourceIndexListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [TableFindIndexBefore::EVENT_NAME => 'filterResources'];
    }

    /**
     * Delete user metadata private & session keys after user is deleted.
     *
     * @param \Cake\Event\Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function filterResources(Event $event): void
    {
        $query = $event->getData('query');
        /** @var \App\Model\Table\Dto\FindIndexOptions $options */
        $options = $event->getData('options');
        $filterValues = $options->getFilter();
        if (isset($filterValues['metadata_key_type'])) {
            $this->filterByMetadataKeyType($query, $filterValues['metadata_key_type']);
        }
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query Query to filter
     * @param string $metadataKeyType filter value
     * @return void
     */
    private function filterByMetadataKeyType(SelectQuery $query, string $metadataKeyType): void
    {
        $fieldAlias = $query->getRepository()->aliasField('metadata_key_type');
        $query->where([$fieldAlias => $metadataKeyType]);
    }
}
