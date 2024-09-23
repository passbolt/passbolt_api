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
namespace Passbolt\Metadata\Service\Folders;

use Cake\Core\Configure;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;

class MetadataFoldersRenderService
{
    /**
     * @param array $folder A folder data to render.
     * @param bool $isV5 is resource with V5 metadata
     * @return array
     */
    public function renderFolder(array $folder, bool $isV5): array
    {
        if ($isV5) {
            $fieldsToRemove = MetadataFolderDto::V4_META_PROPS;
        } else {
            $fieldsToRemove = MetadataFolderDto::V5_META_PROPS;
        }

        foreach ($fieldsToRemove as $fieldToRemove) {
            unset($folder[$fieldToRemove]);
        }

        return $folder;
    }

    /**
     * @param array $folders Folders data to render.
     * @return array
     */
    public function renderFolders(array $folders): array
    {
        $isV5Enabled = Configure::read('passbolt.v5.enabled');
        foreach ($folders as $key => &$folder) {
            // For performance reason, the detection of a v5 resource is made on the
            // presence of metadata
            $isV5 = !empty($folder['metadata']);
            if ($isV5 && !$isV5Enabled) {
                unset($folders[$key]);
            } else {
                $folder = $this->renderFolder($folder, $isV5);
            }
        }

        return $folders;
    }
}
