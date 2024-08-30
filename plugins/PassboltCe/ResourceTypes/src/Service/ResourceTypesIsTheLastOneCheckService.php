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

namespace Passbolt\ResourceTypes\Service;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Service\MetadataSettingsGetService;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class ResourceTypesIsTheLastOneCheckService
{
    use LocatorAwareTrait;

    /**
     * @param \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType type to check
     * @return bool
     */
    public function isLastOfTheDefaultVersion(ResourceType $resourceType): bool
    {
        $v4Types = [
            ResourceType::SLUG_PASSWORD_AND_DESCRIPTION,
            ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP,
            ResourceType::SLUG_PASSWORD_STRING,
            ResourceType::SLUG_STANDALONE_TOTP,
        ];

        $settings = (new MetadataSettingsGetService())->getSettings();

        if ($settings::DEFAULT_RESOURCE_TYPES === $settings::V4) {
            // default is v4 and resource type is of type v4
            if (in_array($resourceType->slug, $v4Types)) {
                $condition = ['slug IN' => $v4Types];
            } else {
                // default is v4 and resource type to delete is v5
                // e.g. not same version family => no count needed
                return false;
            }
        } else {
            // default is v5 and resource type is of v5
            if (!in_array($resourceType->slug, $v4Types)) {
                $condition = ['slug NOT IN' => $v4Types];
            } else {
                // default is v5 and resource type to delete is v4
                // e.g. not same version family => no count needed
                return false;
            }
        }

        /** @var \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable $resourcesTypesTable */
        $resourcesTypesTable = $this->fetchTable('Passbolt/ResourceTypes.ResourceTypes');
        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $count = $resourcesTypesTable->find()->where($condition)->all()->count();

        return $count < 2;
    }

    /**
     * @param \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType type to check
     * @return bool
     */
    public function isTheOnlyOne(ResourceType $resourceType): bool
    {
        /** @var \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable $resourcesTypesTable */
        $resourcesTypesTable = $this->fetchTable('Passbolt/ResourceTypes.ResourceTypes');
        $count = $resourcesTypesTable->find()->all()->count();

        return $count < 2;
    }
}
