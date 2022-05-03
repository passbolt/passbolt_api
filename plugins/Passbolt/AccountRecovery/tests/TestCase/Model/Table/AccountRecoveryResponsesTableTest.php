<?php
declare(strict_types=1);

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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Utility\CleanupTrait;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable
 */
class AccountRecoveryResponsesTableTest extends AccountRecoveryTestCase
{
    use CleanupTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable
     */
    protected $AccountRecoveryResponses;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryResponses = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryResponses');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryResponses);
        parent::tearDown();
    }

    /**
     * Check id field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_Id()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check account_recovery_request_id field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_RequestId()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check responder_foreign_key field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_ResponderForeignKey()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check responder_foreign_model field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_ResponderForeignModel()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check data field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_Data()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check status field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_Status()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check created_by field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_CreatedBy()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check modified_by field validation rules
     */
    public function testAccountRecoveryResponsesTable_Validation_ModifiedBy()
    {
        $this->markTestIncomplete();
    }

    public function testAccountRecoveryResponsesTable_CleanupSecretsHardDeleteRequestsSuccess()
    {
        AccountRecoveryResponseFactory::make()->persist();

        $this->assertEquals(0, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryResponseFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryResponses', 'cleanupHardDeletedAccountRecoveryRequests', 0);
    }
}
