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
namespace Passbolt\Metadata\Service;

use Cake\Core\Configure;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;

class MetadataResourcesRenderService
{
    /**
     * @param array $resource resource to render
     * @param bool $isV5 is resource with V5 metadata
     * @return array
     */
    public function renderResource(array $resource, bool $isV5): array
    {
        if ($isV5) {
            $fieldsToRemove = MetadataResourceDto::V4_META_PROPS;
        } else {
            $fieldsToRemove = MetadataResourceDto::V5_META_PROPS;
        }

        foreach ($fieldsToRemove as $fieldToRemove) {
            unset($resource[$fieldToRemove]);
        }

        return $resource;
    }

    /**
     * @param array $resources resources to render
     * @return array
     */
    public function renderResources(array $resources): array
    {
        $isV5Enabled = Configure::read('passbolt.v5.enabled');
        $result = [];

        foreach ($resources as $resource) {
            // For performance reason, the detection of a v5 resource is made on the
            // presence of metadata
            $isResourceV5 = !empty($resource[MetadataResourceDto::METADATA]);

            // Do not return v5 resource if v5 flag is disabled
            if ($isResourceV5 && !$isV5Enabled) {
                continue;
            }

            $result[] = $this->renderResource($resource, $isResourceV5);
        }

        return $result;
    }
}
