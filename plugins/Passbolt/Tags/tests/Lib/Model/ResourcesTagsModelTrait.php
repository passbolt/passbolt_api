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

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait ResourcesTagsModelTrait
{
    /**
     * Add a dummy resource tag.
     *
     * @param array $data The tag data
     * @param array $options The entity options
     * @return ResourcesTag
     */
    public function addResourceTag(array $data = [], ?array $options = [])
    {
        $resourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        $resourceTag = self::getDummyResourceTagEntity($data, $options);

        $resourcesTagsTable->save($resourceTag, ['checkRules' => false]);

        return $resourceTag;
    }

    /**
     * Get a new resource tag entity
     *
     * @param array $data The tag data.
     * @param array $options The new entity options.
     * @return ResourcesTag
     */
    public function getDummyResourceTagEntity(array $data = [], ?array $options = [])
    {
        $resourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        $defaultOptions = [
            'validate' => false,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummyResourceTagData($data);
        $options = array_merge($defaultOptions, $options);

        return $resourcesTagsTable->newEntity($data, $options);
    }

    /**
     * Get a dummy resource tag with test data.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array ResourcesTag data
     */
    public static function getDummyResourceTagData(array $data = [])
    {
        $entityContent = [
            'resource_id' => UuidFactory::uuid(),
            'tag_id' => UuidFactory::uuid(),
            'user_id' => UuidFactory::uuid(),
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }
}
