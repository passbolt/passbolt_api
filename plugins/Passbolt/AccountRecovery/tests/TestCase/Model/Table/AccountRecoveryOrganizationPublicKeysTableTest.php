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
 * @since         3.4.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPublicKeysTable;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPublicKeysTable
 */
class AccountRecoveryOrganizationPublicKeysTableTest extends AccountRecoveryTestCase
{
    use FormatValidationTrait;

    protected $AccountRecoveryOrganizationPublicKeys;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AccountRecoveryOrganizationPublicKeys') ? [] : [
            'className' => AccountRecoveryOrganizationPublicKeysTable::class
        ];
        $this->AccountRecoveryOrganizationPublicKeys = TableRegistry::getTableLocator()->get('AccountRecoveryOrganizationPublicKeys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryOrganizationPublicKeys);
        parent::tearDown();
    }

    /**
     * Check org id field validation rules
     */
    public function testAccountRecoveryOrganizationPublicKeysTable_ValidationId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryOrganizationPublicKeys,
            'id',
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultData(),
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check org armored key field validation rules
     */
    public function testAccountRecoveryOrganizationPublicKeysTable_ValidationArmoredKey()
    {
        $testCases = [
            'ascii' => self::getAsciiTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryOrganizationPublicKeys,
            'armored_key',
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultData(),
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check org key fingerprint field validation rules
     */
    public function testAccountRecoveryOrganizationPublicKeysTable_ValidationFingerprint()
    {
        $testCases = [
            'ascii' => self::getAsciiTestCases(40),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryOrganizationPublicKeys,
            'fingerprint',
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultData(),
            AccountRecoveryOrganizationPublicKeyFactory::getDefaultOptions(),
            $testCases
        );
    }
}
