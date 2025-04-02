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
namespace Passbolt\Tags\Service\Metadata;

use Cake\Core\Configure;
use Passbolt\Tags\Model\Dto\MetadataTagDto;

class MetadataTagsRenderService
{
    /**
     * @param array $tag A tag data to render.
     * @param bool $isV5 Is tag v5 or not.
     * @return array
     */
    public function renderTag(array $tag, bool $isV5): array
    {
        if ($isV5) {
            $fieldsToRemove = MetadataTagDto::V4_META_PROPS;
        } else {
            $fieldsToRemove = MetadataTagDto::V5_META_PROPS;
        }

        foreach ($fieldsToRemove as $fieldToRemove) {
            unset($tag[$fieldToRemove]);
        }

        return $tag;
    }

    /**
     * @param array $tags Tags data to render.
     * @return array
     */
    public function renderTags(array $tags): array
    {
        $isV5Enabled = Configure::read('passbolt.v5.enabled');
        $result = [];

        foreach ($tags as $tag) {
            // For performance reason, the detection of a v5 resource is made on the
            // presence of metadata
            $isV5 = !empty($tag['metadata']);

            // Do not return v5 resource if v5 flag is disabled
            if ($isV5 && !$isV5Enabled) {
                continue;
            }

            $result[] = $this->renderTag($tag, $isV5);
        }

        return $result;
    }
}
