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
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

class IsV4ToV5UpgradeAllowedRule
{
    /**
     * Checks if v5 resource is updated to v4, fails if settings doesn't allow it.
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        $metadata = $entity->get('metadata');
        $oldMetadata = $entity->getOriginal('metadata');

        if (!self::isV5Upgrade($oldMetadata, $metadata)) {
            return true;
        }

        $metadataTypesSettings = (new MetadataTypesSettingsGetService())->getSettings();

        return $metadataTypesSettings->isV5UpgradeAllowed();
    }

    /**
     * Checks if metadata field value was null and updated value not null then we consider it as an upgrade.
     *
     * @param string|null $oldMetadata Previous metadata field value.
     * @param string|null $metadata Updated metadata field value.
     * @return bool
     */
    public static function isV5Upgrade(?string $oldMetadata, ?string $metadata): bool
    {
        return is_null($oldMetadata) && !is_null($metadata);
    }
}
