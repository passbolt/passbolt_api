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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;

class MaxOfflineItemsRule
{
    use LocatorAwareTrait;

    /**
     * @param \Cake\Datasource\EntityInterface $entity Offline item entity to create.
     * @param array<string, mixed> $options Rule options.
     * @return string|bool True when the user is below the limit, the error message otherwise.
     */
    public function __invoke(EntityInterface $entity, array $options): bool|string
    {
        $userId = $entity->get('user_id');
        if (!is_string($userId) || !Validation::uuid($userId)) {
            return true;
        }

        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting $entity */
        $entity = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings')->find()->first();
        if ($entity === null) {
            return true;
        }

        try {
            $offlineSettingsDto = OfflineSettingsDto::createFromEntity($entity);
        } catch (InvalidArgumentException) {
            // Bad settings values, no need to fail this rule
            return true;
        }

        $maxItems = $offlineSettingsDto->max_items;

        $count = $this->fetchTable('Passbolt/OfflineMode.OfflineItems')
            ->find()
            ->where(['user_id' => $userId])
            ->count();
        if ($count < $maxItems) {
            return true;
        }

        // Replaces the static message declared in buildRules()
        return __('You have reached the maximum number of offline items ({0}).', $maxItems);
    }
}
