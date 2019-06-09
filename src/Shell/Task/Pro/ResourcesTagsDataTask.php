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
        $resourcesTags = [];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.apache'),
            'tag_id' => UuidFactory::uuid('tag.id.#charlie'),
            'user_id' => null,
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.apache'),
            'tag_id' => UuidFactory::uuid('tag.id.#golf'),
            'user_id' => null,
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.gnupg'),
            'tag_id' => UuidFactory::uuid('tag.id.#echo'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.cakephp'),
            'tag_id' => UuidFactory::uuid('tag.id.hotel'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.cakephp'),
            'tag_id' => UuidFactory::uuid('tag.id.hindi'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.ftp'),
            'tag_id' => UuidFactory::uuid('tag.id.alpha'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.git'),
            'tag_id' => UuidFactory::uuid('tag.id.alpha'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.grogle'),
            'tag_id' => UuidFactory::uuid('tag.id.alpha'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.grogle'),
            'tag_id' => UuidFactory::uuid('tag.id.firefox'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.grogle'),
            'tag_id' => UuidFactory::uuid('tag.id.fox-trot'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        $resourcesTags[] = [
            'id' => UuidFactory::uuid(),
            'resource_id' => UuidFactory::uuid('resource.id.grogle'),
            'tag_id' => UuidFactory::uuid('tag.id.hotel'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'created' => '2016-02-20 11:58:30'
        ];

        return $resourcesTags;
    }
}
