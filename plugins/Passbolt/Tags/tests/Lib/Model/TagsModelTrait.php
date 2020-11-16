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
 * @since         2.0.0
 */

namespace Passbolt\Tags\Test\Lib\Model;

use Cake\ORM\TableRegistry;

trait TagsModelTrait
{
    /**
     * Add a personal resource dummy tag for a list of given users.
     *
     * @param array $data The tag data
     * @param string $resourceId The resource to add the tag for
     * @param array $usersIds The list of users ids
     * @return Tag
     */
    public function addResourcePersonalTagFor(array $data, string $resourceId, array $usersIds = [])
    {
        $tag = $this->addTag($data);

        foreach ($usersIds as $userId) {
            $resourceTagData = [
                'resource_id' => $resourceId,
                'tag_id' => $tag->id,
                'user_id' => $userId,
            ];
            $this->addResourceTag($resourceTagData);
        }

        return $tag;
    }

    /**
     * Add a dummy tag.
     *
     * @param array $data The tag data
     * @param array $options The entity options
     * @return Tag
     */
    public function addTag(array $data = [], ?array $options = [])
    {
        $tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $tag = self::getDummyTagEntity($data, $options);

        $tagsTable->save($tag, ['checkRules' => false]);

        return $tag;
    }

    /**
     * Get a new tag entity
     *
     * @param array $data The tag data.
     * @param array $options The new entity options.
     * @return Tag
     */
    public function getDummyTagEntity(array $data = [], ?array $options = [])
    {
        $tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $defaultOptions = [
            'validate' => false,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummyTagData($data);
        $options = array_merge($defaultOptions, $options);

        return $tagsTable->newEntity($data, $options);
    }

    /**
     * Get a dummy tag with test data.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Tag data
     */
    public static function getDummyTagData(array $data = [])
    {
        $entityContent = [
            'slug' => 'Tag slug',
            'is_shared' => false,
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Assert a tag exists for a given user
     *
     * @param string $resourceId The resource id
     * @param string $tagId The tag id
     * @param string $userId The user id
     */
    protected function assertPersonalResourceTagExistsFor(string $resourceId, string $tagId, string $userId)
    {
        $tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $resourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');

        $tag = $tagsTable->find()->where(['id' => $tagId])->count();
        $this->assertNotEmpty($tag);

        $resourceTag = $resourcesTagsTable->find()->where(['resource_id' => $resourceId, 'tag_id' => $tagId, 'user_id' => $userId])->first();
        $this->assertNotEmpty($resourceTag);
    }

    /**
     * Assert a tag does not exist for a given user
     *
     * @param string $resourceId The resource id
     * @param string $tagId The tag id
     * @param string $userId The user id
     */
    protected function assertPersonalResourceTagNotExistFor(string $resourceId, string $tagId, string $userId)
    {
        $resourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        $resourceTag = $resourcesTagsTable->find()->where(['resource_id' => $resourceId, 'tag_id' => $tagId, 'user_id' => $userId])->first();
        $this->assertEmpty($resourceTag);
    }
}
