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
 * @since         4.1.0
 */

namespace Passbolt\Tags\Service\Tags;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Tags\Model\Entity\Tag;

class UpdatePersonalTagService
{
    use LocatorAwareTrait;

    /**
     * Update personal tag
     *
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @param string $slug Slug(tag name) to update with.
     * @param \Passbolt\Tags\Model\Entity\Tag $tag The tag entity to update.
     * @return \Passbolt\Tags\Model\Entity\Tag|bool The updated tag entity.
     * @throws \Cake\Http\Exception\BadRequestException If a non admin tries to change a personal tag into a shared tag.
     * @throws \Exception
     */
    public function update(UserAccessControl $uac, string $slug, Tag $tag)
    {
        if (mb_substr($slug, 0, 1) === '#') {
            throw new BadRequestException('You do not have the permission to change a personal tag into shared tag.');
        }

        /** @var \Passbolt\Tags\Model\Table\TagsTable $tagsTable */
        $tagsTable = $this->fetchTable('Passbolt/Tags.Tags');
        /** @var \Passbolt\Tags\Model\Table\ResourcesTagsTable $resourcesTagsTable */
        $resourcesTagsTable = $this->fetchTable('Passbolt/Tags.ResourcesTags');

        return $tagsTable->getConnection()->transactional(function () use (
            $tagsTable,
            $resourcesTagsTable,
            $tag,
            $slug,
            $uac
        ) {
            $newTag = $tagsTable->findOrCreateTag($slug, $uac);

            // Update all the tag association to the new tag id
            $resourcesTagsTable->updateUserTag(
                $uac->getId(),
                $tag->get('id'),
                $newTag->get('id')
            );

            // Flush all unused tags
            $tagsTable->deleteAllUnusedTags();

            return $newTag;
        });
    }
}
