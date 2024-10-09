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

namespace Passbolt\Metadata\Model\Rule;

use Cake\ORM\Entity;
use Passbolt\Metadata\Service\MetadataKeysSettingsGetService;

class IsMetadataKeyTypeAllowedBySettingsRule
{
    /**
     * Performs the check
     *
     * Checks that if the `metadata_key_type` is 'user_key', the entity is not shared with
     * other users, or a group
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        // If the resource's metadata key type is not personal, the present rule does not apply
        $isPersonal = $entity->get('metadata_key_type') === 'user_key';
        if (!$isPersonal) {
            return true;
        }

        // Should be cached if doing multiple insert, but not needed atm
        $settings = (new MetadataKeysSettingsGetService())->getSettings();

        return $settings->isUsageOfPersonalKeysAllowed();
    }
}
