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
namespace Passbolt\Tags\Test\TestCase\Model\Table;

use App\Error\Exception\ValidationRuleException;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Model\Table\TagsTable;
use Passbolt\Tags\Test\Lib\TagTestCase;

/**
 * App\Model\Table\TagsTable Test Case
 */
class TagsTableTest extends TagTestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TagsTable
     */
    public $Tags;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/resources', 'app.Base/groups',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Tags') ? [] : ['className' => TagsTable::class];
        $this->Tags = TableRegistry::get('Tags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tags);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testTagsTableBuildEntitiesOrFailError()
    {
        try {
            $tags = [['test']];
            $this->Tags->buildEntitiesOrFail($tags);
            $this->fail('Build entities should throw an exception');
        } catch (ValidationRuleException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testTagsTableBeforeMarshall()
    {
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true
        ]);
        $this->assertEmpty($tag->toArray());
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true
        ], [
            'accessibleFields' => [
                'id' => true,
                'slug' => true,
                'is_shared' => true
            ]
        ]);
        $this->assertNotEmpty($tag->id);
        $this->assertEquals($tag->id, UuidFactory::uuid('tag.id.test'));
        $this->assertFalse($tag->is_shared);
    }

    public function testTagsTableCalculateChanges()
    {
        // Test delete and add and unchange
        $current = [
            0 => ['id' => UuidFactory::uuid('tag1')],
            1 => ['id' => UuidFactory::uuid('tag2')]
        ];
        $new = [
            0 => ['id' => UuidFactory::uuid('tag3')],
            1 => ['id' => UuidFactory::uuid('tag2')]
        ];
        $expect = [
            'created' => [['id' => UuidFactory::uuid('tag3')]],
            'deleted' => [['id' => UuidFactory::uuid('tag1')]],
            'unchanged' => [['id' => UuidFactory::uuid('tag2')]]
        ];
        $result = $this->Tags->calculateChanges($current, $new);
        $this->assertEquals($expect, $result);

        // Test add when there is currently no tag
        $current = [];
        $new = [0 => ['id' => UuidFactory::uuid('tag1')]];
        $expect = [
            'created' => [['id' => UuidFactory::uuid('tag1')]],
            'deleted' => [],
            'unchanged' => []
        ];
        $result = $this->Tags->calculateChanges($current, $new);
        $this->assertEquals($expect, $result);

        // Test delete all
        $current = [0 => ['id' => UuidFactory::uuid('tag1')]];
        $new = [];
        $expect = [
            'created' => [],
            'deleted' => [['id' => UuidFactory::uuid('tag1')]],
            'unchanged' => []
        ];
        $result = $this->Tags->calculateChanges($current, $new);
        $this->assertEquals($expect, $result);

        // Test unchange all
        $current = [0 => ['id' => UuidFactory::uuid('tag1')]];
        $new = $current;
        $expect = [
            'created' => [],
            'deleted' => [],
            'unchanged' => [['id' => UuidFactory::uuid('tag1')]]
        ];
        $result = $this->Tags->calculateChanges($current, $new);
        $this->assertEquals($expect, $result);
    }

    public function testDeleteAllUnusedTags()
    {
        // unused and #unused
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 2);

        // there should not be any left
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 0);
    }
}
