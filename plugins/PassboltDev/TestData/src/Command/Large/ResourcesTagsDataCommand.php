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
namespace Passbolt\TestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Passbolt\TestData\Lib\DataCommand;

class ResourcesTagsDataCommand extends DataCommand
{
    public $entityName = 'ResourcesTags';

    /**
     * Get the resources tags data
     *
     * @return array
     */
    public function getData(): array
    {
        $resourcesTags = [];
        $resourcesTags = array_merge($resourcesTags, $this->getResourcesTagsScenarioForPersonalTags());
        $resourcesTags = array_merge($resourcesTags, $this->getResourcesTagsScenarioForSharedTags());

        return $resourcesTags;
    }

    public function getResourcesTagsScenarioForPersonalTags()
    {
        $resourcesTags = [];

        $usersTable = $this->fetchTable('Users');
        $resourcesTable = $this->fetchTable('Resources');
        $tagsTable = $this->fetchTable('Tags');

        $users = $usersTable->findIndex(Role::USER);
        foreach ($users as $user) {
            $tags = $tagsTable->find()->where(['slug LIKE' => "{$user->username}%"])->all();
            $options['order']['Resources.modified'] = true;
            $resources = $resourcesTable->findIndex($user->id, $options);
            foreach ($resources as $resource) {
                foreach ($tags as $tag) {
                    $resourcesTags[] = [
                        'id' => UuidFactory::uuid('resource_tag.id.' . $resource->id . '-' . $user->id . '-' . $tag->id),
                        'resource_id' => $resource->id,
                        'tag_id' => $tag->id,
                        'user_id' => $user->id,
                        'created' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        return $resourcesTags;
    }

    public function getResourcesTagsScenarioForSharedTags()
    {
        $resourcesTags = [];

        $resourcesTable = $this->fetchTable('Resources');
        $tagsTable = $this->fetchTable('Tags');

        $tags = $tagsTable->find()->where(['is_shared' => true])->all();
        $resources = $resourcesTable->find()->all();
        foreach ($resources as $resource) {
            foreach ($tags as $tag) {
                $resourcesTags[] = [
                    'id' => UuidFactory::uuid('resource_tag.id.' . $resource->id . '-' . $tag->id),
                    'resource_id' => $resource->id,
                    'tag_id' => $tag->id,
                    'user_id' => null,
                    'created' => date('Y-m-d H:i:s'),
                ];
            }
        }

        return $resourcesTags;
    }
}
