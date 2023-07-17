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
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
 */
class AccountRecoveryRequestsTableTest extends AccountRecoveryTestCase
{
    use CleanupTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
     */
    protected $AccountRecoveryRequests;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryRequests = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryRequests');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryRequests);
        parent::tearDown();
    }

    /**
     * Check id field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_Id()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check user_id field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_UserId()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check armored_key field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_ArmoredKey()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check fingerprint field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_Fingerprint()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check authentication_token_id field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_AuthenticationTokenId()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check status field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_Status()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check created_by field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_CreatedBy()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check modified_by field validation rules
     */
    public function testAccountRecoveryRequestsTable_Validation_ModifiedBy()
    {
        $this->markTestIncomplete();
    }

    public function testAccountRecoveryRequestsTable_CleanupSecretsSoftDeletedUsersSuccess()
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        AccountRecoveryRequestFactory::make()
            ->setField('user_id', $user->id)
            ->persist();

        $this->assertEquals(1, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryRequests', 'cleanupSoftDeletedUsers', 0);
    }

    public function testAccountRecoveryRequestsTable_CleanupSecretsHardDeletedUsersSuccess()
    {
        AccountRecoveryRequestFactory::make()->persist();

        $this->assertEquals(0, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryRequests', 'cleanupHardDeletedUsers', 0);
    }
}
