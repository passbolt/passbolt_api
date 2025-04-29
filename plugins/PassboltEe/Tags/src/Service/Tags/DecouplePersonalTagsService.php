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
 * @since         4.12.0
 */

namespace Passbolt\Tags\Service\Tags;

use App\Utility\UuidFactory;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Model\Table\ResourcesTagsTable;
use Passbolt\Tags\Model\Table\TagsTable;

class DecouplePersonalTagsService
{
    use LocatorAwareTrait;

    private ResourcesTagsTable $ResourceTags;
    private TagsTable $Tags;

    /**
     * DevelopTagsService
     */
    public function __construct()
    {
        $this->ResourceTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        $this->Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');

        $this->Tags->setTable('tags');
        $this->ResourceTags->setTable('resources_tags');
    }

    /**
     * Creates new tags for users sharing personal tags
     * No personal tags should be shared with other users
     *
     * @return int the number of decoupled tags
     */
    public function decouple(): int
    {
        $personalTagsToDecouple = 0;

        // Get all tags to be created to have one personal tag per user
        $tagsDecoupledByUsers = $this->findPrivateTagsDecoupledByUserId();
        if (empty($tagsDecoupledByUsers)) {
            return $this->logPersonalTagsToDecouple(0);
        }

        // This will collect all the tags to be created. It will be executed at the end on this method
        $insertTagsQuery = $this->Tags->getConnection()->insertQuery()
            ->insert(['id', 'slug', 'is_shared'], ['is_shared' => 'boolean'])
            ->into('tags');

        foreach ($tagsDecoupledByUsers as $tag) {
            $resourceTags = $tag['resources_tags'] ?? [];
            if (count($resourceTags) < 2) {
                // Either the tag has no pivot entries, it will be dropped at the end of this method
                // Or since there was only one user_id - tag_id pair in the resource tags, the personal tag was not shared
                // Since the tag is not shared by other users, no tags are created, go to next tag
                continue;
            }
            // The first resources_tags stays untouched, no tag needs to be created for it
            array_shift($resourceTags);

            // Create a new tag for each additional user_id - tag_id pair (ResourcesTags were grouped along these two fields)
            foreach ($resourceTags as $resourcesTag) {
                $personalTagsToDecouple++;
                $newTagId = UuidFactory::uuid();
                // Add the tag to be created in the insert query
                $newTag = array_merge($tag, ['id' => $newTagId, 'user_id' => $resourcesTag['user_id'],]);
                $insertTagsQuery->values($newTag);

                $this->ResourceTags->getConnection()
                    ->updateQuery()
                    ->update('resources_tags')
                    ->set(['tag_id' => $newTagId])
                    ->where([
                        'tag_id' => $resourcesTag['tag_id'],
                        'user_id' => $resourcesTag['user_id'],
                    ])
                    ->execute();
            }
        }
        if ($personalTagsToDecouple > 0) {
            $insertTagsQuery->execute();
        }
        $this->Tags->deleteAllUnusedTags();

        return $this->logPersonalTagsToDecouple($personalTagsToDecouple);
    }

    /**
     * @return array
     */
    private function findPrivateTagsDecoupledByUserId(): array
    {
        return $this->Tags->find()
            ->select([
                'Tags.id',
                'Tags.slug',
                'Tags.is_shared',
            ])
            ->where(['Tags.is_shared' => false])
            ->whereNotNull('Tags.slug')
            ->contain('ResourcesTags', function (Query $q) {
                return $q
                    ->select([
                        'ResourcesTags.user_id',
                        'ResourcesTags.tag_id',
                    ])
                    ->whereNotNull('ResourcesTags.user_id')
                    ->groupBy([
                        'ResourcesTags.user_id',
                        'ResourcesTags.tag_id',
                    ]);
            })
            ->disableHydration()
            ->all()
            ->toArray();
    }

    /**
     * @param int $numberOfPersonalTagsToDecouple number of personal tags to be decoupled
     * @return int
     */
    private function logPersonalTagsToDecouple(int $numberOfPersonalTagsToDecouple): int
    {
        Log::info($numberOfPersonalTagsToDecouple . ' personal tags were found to decouple.');

        return $numberOfPersonalTagsToDecouple;
    }
}
