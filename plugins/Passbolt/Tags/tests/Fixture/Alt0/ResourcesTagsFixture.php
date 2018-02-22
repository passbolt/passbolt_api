<?php
namespace Passbolt\Tags\Test\Fixture\Alt0;

use App\Utility\UuidFactory;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourcesTagsFixture
 *
 */
class ResourcesTagsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'resource_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'tag_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'id' => ['type' => 'index', 'columns' => ['id', 'user_id', 'resource_id', 'tag_id'], 'length' => []],
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
                'id' => UuidFactory::uuid('apache.alpha.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.apache'),
                'tag_id' => UuidFactory::uuid('tag.id.alpha'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('apache.#echo.null'),
                'resource_id' => UuidFactory::uuid('resource.id.apache'),
                'tag_id' => UuidFactory::uuid('tag.id.#echo'),
                'user_id' => null,
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('apache.#bravo.null'),
                'resource_id' => UuidFactory::uuid('resource.id.apache'),
                'tag_id' => UuidFactory::uuid('tag.id.#bravo'),
                'user_id' => null,
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('apache.fox-trot.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.apache'),
                'tag_id' => UuidFactory::uuid('tag.id.fox-trot'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('april.alpha.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.april'),
                'tag_id' => UuidFactory::uuid('tag.id.alpha'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('april.#bravo.null'),
                'resource_id' => UuidFactory::uuid('resource.id.april'),
                'tag_id' => UuidFactory::uuid('tag.id.#bravo'),
                'user_id' => null,
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('cakephp.#charlie.null'),
                'resource_id' => UuidFactory::uuid('resource.id.cakephp'),
                'tag_id' => UuidFactory::uuid('tag.id.#charlie'),
                'user_id' => null,
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('chai.alpha.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.chai'),
                'tag_id' => UuidFactory::uuid('tag.id.alpha'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('chai.alpha.betty'),
                'resource_id' => UuidFactory::uuid('resource.id.chai'),
                'tag_id' => UuidFactory::uuid('tag.id.alpha'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('chai.hotel.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.chai'),
                'tag_id' => UuidFactory::uuid('tag.id.hotel'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('grogle.#golf.null'),
                'resource_id' => UuidFactory::uuid('resource.id.grogle'),
                'tag_id' => UuidFactory::uuid('tag.id.#golf'),
                'user_id' => null,
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('grogle.firefox.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.grogle'),
                'tag_id' => UuidFactory::uuid('tag.id.firefox'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ],
            [
                'id' => UuidFactory::uuid('grogle.hindi.ada'),
                'resource_id' => UuidFactory::uuid('resource.id.grogle'),
                'tag_id' => UuidFactory::uuid('tag.id.hindi'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'created' => '2016-01-29 13:39:25'
            ]
        ];
        parent::init();
    }
}
