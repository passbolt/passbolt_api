<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Shell\Task\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class ResourcesTagsDataTask extends DataTask
{
    public $entityName = 'ResourcesTags';

    /**
     * Get the resources tags data
     *
     * @return array
     */
    public function getData()
    {
        $resourcesTags = [];
        $resourcesTags = array_merge($resourcesTags, $this->getResourcesTagsScenarioForPersonalTags());
        $resourcesTags = array_merge($resourcesTags, $this->getResourcesTagsScenarioForSharedTags());

        return $resourcesTags;
    }

    public function getResourcesTagsScenarioForPersonalTags()
    {
        $resourcesTags = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');
        $this->loadModel('Tags');

        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            $tags = $this->Tags->find()->where(['slug LIKE' => "{$user->username}%"])->all();
            $options['order']['Resources.modified'] = true;
            $resources = $this->Resources->findIndex($user->id, $options);
            foreach ($resources as $resource) {
                foreach ($tags as $tag) {
                    $resourcesTags[] = [
                        'id' => UuidFactory::uuid('resource_tag.id.' . $resource->id . '-' . $user->id . '-' . $tag->id),
                        'resource_id' => $resource->id,
                        'tag_id' => $tag->id,
                        'user_id' => $user->id,
                        'created' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        return $resourcesTags;
    }

    public function getResourcesTagsScenarioForSharedTags()
    {
        $resourcesTags = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');
        $this->loadModel('Tags');

        $tags = $this->Tags->find()->where(['is_shared' => true])->all();
        $resources = $this->Resources->find()->all();
        foreach ($resources as $resource) {
            foreach ($tags as $tag) {
                $resourcesTags[] = [
                    'id' => UuidFactory::uuid('resource_tag.id.' . $resource->id . '-' . $tag->id),
                    'resource_id' => $resource->id,
                    'tag_id' => $tag->id,
                    'user_id' => null,
                    'created' => date('Y-m-d H:i:s')
                ];
            }
        }

        return $resourcesTags;
    }
}
