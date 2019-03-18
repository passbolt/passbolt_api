<?php
namespace Passbolt\DirectorySync\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Model\Table\DirectoryReportsItemsTable;

/**
 * Passbolt\DirectorySync\Model\Table\DirectoryReportsItemsTable Test Case
 */
class DirectoryReportsItemsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryReportsItemsTable
     */
    public $DirectoryReportsItems;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        //'plugin.passbolt/directory_sync.directory_reports_items',
        //'plugin.passbolt/directory_sync.reports'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DirectoryReportsItems') ? [] : ['className' => DirectoryReportsItemsTable::class];
        $this->DirectoryReportsItems = TableRegistry::getTableLocator()->get('DirectoryReportsItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DirectoryReportsItems);

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
