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
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class IsResourceV5ToV4DowngradeAllowedRule
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
        $resourceTypeId = $entity->get('resource_type_id');
        $oldResourceTypeId = $entity->getOriginal('resource_type_id');

        if (!self::isResourceTypeChangeToV4($oldResourceTypeId, $resourceTypeId)) {
            return true;
        }

        $metadataTypesSettings = (new MetadataTypesSettingsGetService())->getSettings();

        return $metadataTypesSettings->isV4DowngradeAllowed();
    }

    /**
     * Checks if resource type is changed from V5 to V4. If it is the case then we consider it as a downgrade.
     *
     * @param string|null $oldResourceTypeId Previous resource type identifier.
     * @param string|null $updatedResourceTypeId Updated resource type identifier.
     * @return bool
     * @throws \Exception
     */
    public static function isResourceTypeChangeToV4(?string $oldResourceTypeId, ?string $updatedResourceTypeId): bool
    {
        $v5ResourceTypes = ResourceType::getV5ResourceTypes();
        $v4ResourceTypes = ResourceType::getV4ResourceTypes();

        return in_array($oldResourceTypeId, $v5ResourceTypes) && in_array($updatedResourceTypeId, $v4ResourceTypes);
    }
}
