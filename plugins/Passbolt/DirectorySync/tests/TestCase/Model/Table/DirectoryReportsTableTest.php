<?php
namespace Passbolt\DirectorySync\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Model\Table\DirectoryReportsTable;

/**
 * Passbolt\DirectorySync\Model\Table\DirectoryReportsTable Test Case
 */
class DirectoryReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryReportsTable
     */
    public $DirectoryReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        //'plugin.passbolt/directory_sync.directory_reports'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DirectoryReports') ? [] : ['className' => DirectoryReportsTable::class];
        $this->DirectoryReports = TableRegistry::getTableLocator()->get('DirectoryReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DirectoryReports);

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
