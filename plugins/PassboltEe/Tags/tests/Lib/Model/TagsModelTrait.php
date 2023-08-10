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
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

trait TagsModelTrait
{
    /**
     * Add a personal resource dummy tag for a list of given users.
     *
     * @param array $data The tag data
     * @param string $resourceId The resource to add the tag for
     * @param array $usersIds The list of users ids
     * @return \Passbolt\Tags\Model\Entity\Tag
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
     * @return \Passbolt\Tags\Model\Entity\Tag
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
     * @return \Passbolt\Tags\Model\Entity\Tag
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
     * Assert a tag exists
     *
     * @param string $tagId The tag id
     */
    protected function assertTagExists(string $tagId)
    {
        $tag = TagFactory::find()->where(['id' => $tagId])->count();
        $this->assertNotEmpty($tag, 'Expect an existing tag');
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
        $this->assertTagExists($tagId);

        $resourceTag = ResourcesTagFactory::find()->where(['resource_id' => $resourceId, 'tag_id' => $tagId, 'user_id' => $userId])->first();
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
        $resourceTag = ResourcesTagFactory::find()->where(['resource_id' => $resourceId, 'tag_id' => $tagId, 'user_id' => $userId])->first();
        $this->assertEmpty($resourceTag);
    }
}
