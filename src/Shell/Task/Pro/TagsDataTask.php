<?php
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
namespace PassboltTestData\Shell\Task\Pro;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class TagsDataTask extends DataTask
{
    public $entityName = 'Tags';

    /**
     * Get the tags data
     *
     * @return array
     */
    public function getData()
    {
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.alpha'),
            'slug' => 'alpha',
            'is_shared' => 0
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.#bravo'),
            'slug' => '#bravo',
            'is_shared' => 1
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.#charlie'),
            'slug' => '#charlie',
            'is_shared' => 1
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.#echo'),
            'slug' => '#echo',
            'is_shared' => 1
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.fox-trot'),
            'slug' => 'fox-trot',
            'is_shared' => 0
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.firefox'),
            'slug' => 'firefox',
            'is_shared' => 0
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.#golf'),
            'slug' => '#golf',
            'is_shared' => 1
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.hotel'),
            'slug' => '#hotel',
            'is_shared' => 1
        ];
        $tags[] = [
            'id' => UuidFactory::uuid('tag.id.hindi'),
            'slug' => 'परदेशी-परदेशी',
            'is_shared' => 0
        ];

        return $tags;
    }
}
