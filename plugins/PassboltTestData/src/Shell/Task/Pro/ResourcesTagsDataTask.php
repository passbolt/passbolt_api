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
namespace PassboltTestData\Shell\Task\Pro;

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
        $Resources = $this->loadModel('Resources');
        $Tags = $this->loadModel('Tags');
        $Users = $this->loadModel('Users');
        $tags = $Tags->find('all')
            ->all()
            ->toArray();
        $personalTags = array_filter($tags, function ($tag) {
            return preg_match('/^[^#]{1}/', $tag->slug);
        });
        $sharedTags = array_diff($tags, $personalTags);
        $resources = $Resources->find('all')
            ->where(['deleted' => false])
            ->all()
            ->toArray();
        $resourcesTags = [];

        foreach ($resources as $resource) {
            $users = $Users->findIndex(Role::USER, [
                'has-access' => [$resource->id]
            ]);

            // Insert shared tags.
            $count = rand(0, 2);
            if ($count) {
                $randTags = array_rand($sharedTags, $count);
                if (!is_array($randTags)) {
                    $randTags = [$randTags];
                }
                foreach ($randTags as $randKey) {
                    $resourcesTags[] = [
                        'id' => UuidFactory::uuid(),
                        'resource_id' => $resource->id,
                        'tag_id' => $sharedTags[$randKey]->id,
                        'user_id' => null,
                        'created' => '2016-02-20 11:58:30'
                    ];
                }
            }

            // Insert personal tags.
            foreach ($users as $user) {
                $count = rand(0, 3);
                if (!$count) {
                    continue;
                }
                $randTags = array_rand($personalTags, $count);
                if (!is_array($randTags)) {
                    $randTags = [$randTags];
                }
                foreach ($randTags as $randKey) {
                    $resourcesTags[] = [
                        'id' => UuidFactory::uuid(),
                        'resource_id' => $resource->id,
                        'tag_id' => $personalTags[$randKey]->id,
                        'user_id' => $user->id,
                        'created' => '2016-02-20 11:58:30'
                    ];
                }
            }
        }

        return $resourcesTags;
    }
}
