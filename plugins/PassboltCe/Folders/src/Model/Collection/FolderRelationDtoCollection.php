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
 * @since         3.10.0
 */

namespace Passbolt\Folders\Model\Collection;

use Cake\Collection\Collection;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FolderRelationDtoCollection extends Collection
{
    /**
     * Callback to map items and return their foreign id.
     *
     * @param \Passbolt\Folders\Model\Dto\FolderRelationDto $folderRelationDto The folder relation to map.
     * @return string|null
     */
    public static function mapForeignId(FolderRelationDto $folderRelationDto): ?string
    {
        return $folderRelationDto->foreignId;
    }

    /**
     * Callback to filter by items relative to folders.
     *
     * @param \Passbolt\Folders\Model\Dto\FolderRelationDto $folderRelationDto The folder relation to filter.
     * @return bool
     */
    public static function filterByFolder(FolderRelationDto $folderRelationDto): bool
    {
        return $folderRelationDto->foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER;
    }

    /**
     * Check if the collection contains an item relative to a folder.
     *
     * @return bool
     */
    public function containsFolder(): bool
    {
        return $this->some([self::class, 'filterByFolder']);
    }

    /**
     * Get the identifiers of the folders present in the list.
     *
     * @return array
     */
    public function getFoldersIds(): array
    {
        return $this->filter([FolderRelationDtoCollection::class, 'filterByFolder'])
            ->map([FolderRelationDtoCollection::class, 'mapForeignId'])
            ->toArray();
    }
}
