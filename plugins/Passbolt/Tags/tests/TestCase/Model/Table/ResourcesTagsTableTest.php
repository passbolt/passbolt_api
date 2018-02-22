<?php
namespace Passbolt\Tags\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\Tags\Model\Table\ResourcesTagsTable;

/**
 * App\Model\Table\ResourcesTagsTable Test Case
 */
class ResourcesTagsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesTagsTable
     */
    public $ResourcesTags;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/roles',
        'app.Base/users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ResourcesTags') ? [] : ['className' => ResourcesTagsTable::class];
        $this->ResourcesTags = TableRegistry::get('ResourcesTags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResourcesTags);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
