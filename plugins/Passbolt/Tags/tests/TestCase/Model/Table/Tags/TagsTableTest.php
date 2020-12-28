<?php
declare(strict_types=1);

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
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use App\Error\Exception\CustomValidationException;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
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
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Resources',
        'app.Base/ResourceTypes', 'app.Base/Groups',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
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
            $this->Tags->buildEntitiesOrFail(UuidFactory::uuid('user.id.ada'), $tags);
            $this->fail('Build entities should throw an exception');
        } catch (CustomValidationException $e) {
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
        $userId = UuidFactory::uuid('user.id.ada');
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true,
            'user_id' => $userId,
        ]);
        $this->assertEmpty($tag->toArray());
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true,
            'user_id' => $userId,
        ], [
            'accessibleFields' => [
                'id' => true,
                'user_id' => true,
                'slug' => true,
                'is_shared' => true,
            ],
        ]);
        $this->assertNotEmpty($tag->id);
        $this->assertEquals($tag->id, UuidFactory::uuid('tag.id.test'));
        $this->assertFalse($tag->is_shared);
    }

    public function testTagsDeleteAllUnusedTags()
    {
        // unused and #unused
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 2);

        // there should not be any left
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 0);
    }
}
