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

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Model\Entity\Tag;

class UpdatePersonalTagService
{
    use LocatorAwareTrait;
    use MetadataSettingsAwareTrait;

    /**
     * Update personal tag
     *
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @param \Passbolt\Tags\Model\Dto\MetadataTagDto $tagDto Tag DTO.
     * @param \Passbolt\Tags\Model\Entity\Tag $tag The tag entity to update.
     * @return \Passbolt\Tags\Model\Entity\Tag|bool The updated tag entity.
     * @throws \Cake\Http\Exception\BadRequestException If a non admin tries to change a personal tag into a shared tag.
     * @throws \App\Error\Exception\CustomValidationException If unable to save tag entity due to validation errors.
     * @throws \Exception
     */
    public function update(UserAccessControl $uac, MetadataTagDto $tagDto, Tag $tag)
    {
        $dtoArray = $tagDto->toArray();
        /** @var \Passbolt\Tags\Model\Table\TagsTable $tagsTable */
        $tagsTable = $this->fetchTable('Passbolt/Tags.Tags');

        if ($tagDto->isV5()) {
            $this->assertV5TagCreationEnabled();

            $options = [
                'accessibleFields' => [
                    'slug' => true,
                    'metadata' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'is_shared' => true,
                ],
                'validate' => 'v5',
            ];
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $tagsTable->rulesChecker();
            $tagsTable->buildRulesV5($rules);

            $entity = $tagsTable->patchEntity(
                $tag,
                [
                    'slug' => null,
                    'metadata' => $dtoArray['metadata'],
                    'metadata_key_id' => $dtoArray['metadata_key_id'],
                    'metadata_key_type' => $dtoArray['metadata_key_type'],
                    'is_shared' => $dtoArray['is_shared'],
                ],
                $options
            );

            if (!$tagsTable->save($entity)) {
                throw new CustomValidationException(
                    __('Unable to save the tag.'),
                    $entity->getErrors(),
                    $tagsTable
                );
            }

            return $entity;
        } else {
            $this->assertV4TagCreationEnabled();

            $slug = $dtoArray['slug'];

            if (!is_null($slug) && mb_substr($slug, 0, 1) === '#') {
                throw new BadRequestException(
                    __('You do not have the permission to change a personal tag into shared tag.')
                );
            }

            $this->assertV4DowngradeAllowed($tag);

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

                /**
                 * Update all the tag association to the new tag id (only if new tag is created).
                 *
                 * Note: There is a case where the slug provided by user is already exist, in that case we don't perform the update.
                 */
                if ($tag->get('id') !== $newTag->get('id')) {
                    $resourcesTagsTable->updateUserTag(
                        $uac->getId(),
                        $tag->get('id'),
                        $newTag->get('id')
                    );
                }

                // Flush all unused tags
                $tagsTable->deleteAllUnusedTags();

                return $newTag;
            });
        }
    }

    /**
     * @param \Passbolt\Tags\Model\Entity\Tag $tag Existing tag entity.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If v5_to_v4_downgrade is disabled.
     */
    private function assertV4DowngradeAllowed(Tag $tag): void
    {
        // We consider a tag downgrade when current tag's slug is null and request contains slug field (not empty)
        if (!is_null($tag->slug)) {
            return;
        }

        $metadataTypesSettings = MetadataTypesSettingsGetService::getSettings();

        if (!$metadataTypesSettings->isV4DowngradeAllowed()) {
            throw new BadRequestException(__('The settings selected by your administrator prevent from downgrading tag.')); // phpcs:ignore
        }
    }
}
