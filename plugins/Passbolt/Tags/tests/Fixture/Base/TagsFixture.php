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
namespace Passbolt\Tags\Test\Fixture\Base;

use App\Utility\UuidFactory;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * TagsFixture
 *
 */
class TagsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'slug' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'is_shared' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'id' => ['type' => 'index', 'columns' => ['id', 'slug'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init the records dynamically
     *
     * @throws \Exception if dependencies to generate random uuid are not met
     */
    public function init()
    {
        $this->records = [
            [
                'id' => UuidFactory::uuid('tag.id.alpha'),
                'slug' => 'alpha',
                'is_shared' => 0
            ],
            [
                'id' => UuidFactory::uuid('tag.id.#bravo'),
                'slug' => '#bravo',
                'is_shared' => 1
            ],
            [
                'id' => UuidFactory::uuid('tag.id.#charlie'),
                'slug' => '#charlie',
                'is_shared' => 1
            ],
            [
                'id' => UuidFactory::uuid('tag.id.#echo'),
                'slug' => '#echo',
                'is_shared' => 1
            ],
            [
                'id' => UuidFactory::uuid('tag.id.fox-trot'),
                'slug' => 'fox-trot',
                'is_shared' => 0
            ],
            [
                'id' => UuidFactory::uuid('tag.id.firefox'),
                'slug' => 'firefox',
                'is_shared' => 0
            ],
            [
                'id' => UuidFactory::uuid('tag.id.#golf'),
                'slug' => '#golf',
                'is_shared' => 1
            ],
            [
                'id' => UuidFactory::uuid('tag.id.hotel'),
                'slug' => 'hotel',
                'is_shared' => 1
            ],
            [
                'id' => UuidFactory::uuid('tag.id.hindi'),
                'slug' => 'परदेशी-परदेशी',
                'is_shared' => 0
            ],
            [
                'id' => UuidFactory::uuid('tag.id.unused'),
                'slug' => 'unused',
                'is_shared' => 0
            ],
            [
                'id' => UuidFactory::uuid('tag.id.#unused'),
                'slug' => '#unused',
                'is_shared' => 1
            ]
        ];
        parent::init();
    }
}
