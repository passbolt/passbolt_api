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
 * @since         2.14.0
 */
namespace Passbolt\Folders\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class FoldersRelationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    public $FoldersRelations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Passbolt/Folders.Folders',
        'plugin.Passbolt/Folders.FoldersRelations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FoldersRelations);

        parent::tearDown();
    }
}
