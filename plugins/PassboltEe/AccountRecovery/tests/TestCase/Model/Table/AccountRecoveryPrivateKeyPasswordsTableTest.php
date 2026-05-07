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
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
 */
class AccountRecoveryPrivateKeyPasswordsTableTest extends AccountRecoveryTestCase
{
    use CleanupTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
     */
    protected $AccountRecoveryPrivateKeyPasswords;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryPrivateKeyPasswords = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryPrivateKeyPasswords);
        parent::tearDown();
    }

    /**
     * @return array Default options
     */
    private function getDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    /**
     * Check org id field validation rules
     */
    public function testAccountRecoveryPrivateKeyPasswordsTable_ValidationId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryPrivateKeyPasswords,
            'id',
            AccountRecoveryPrivateKeyPasswordFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check recipient foreign key validation rules
     */
    public function testAccountRecoveryPrivateKeyPasswordsTable_ValidationForeignKey()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'fingerprint' => [
                'rule_name' => 'invalidFingerprint',
                'test_cases' => [
                    '67BFFCB7B74AF4C85E81AB26508850525CD78BA' => false,
                    '67BFFCB7B74AF4C85E81AB26508850525CD78BAAA' => false,
                    '67BFFCB7B74AF4C85E81AB26508850525CD78BAZ' => false,
                    '67BFFCB7B74AF4C85E81AB26508850525CD78BAA' => true,
                ],
            ],
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryPrivateKeyPasswords,
            'recipient_fingerprint',
            AccountRecoveryPrivateKeyPasswordFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check recipient foreign model validation rules
     */
    public function testAccountRecoveryPrivateKeyPasswordsTable_ValidationRecipientForeignModel()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(AccountRecoveryPrivateKeyPassword::ALLOWED_RECIPIENT_FOREIGN_MODELS),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryPrivateKeyPasswords,
            'recipient_foreign_model',
            AccountRecoveryPrivateKeyPasswordFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check data validation rules
     */
    public function testAccountRecoveryPrivateKeyPasswordsTable_ValidationData()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'isValidOpenPGPMessage' => [
                'rule_name' => 'isValidOpenPGPMessage',
                'test_cases' => [
                    '🔥' => false,
                    '-' => false,
                    '-----BEGIN PGP MESSAGE-----' => false,
                    '-----BEGIN PGP PUBLIC KEY BLOCK-----' => false,
                    '-----BEGIN PGP PRIVATE KEY BLOCK-----' => false,
                    (string)file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key') => false,
                    (string)file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg') => true,
                    (string)file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password.msg') => true,
                ],
            ],
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryPrivateKeyPasswords,
            'data',
            AccountRecoveryPrivateKeyPasswordFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    public function testAccountRecoveryPrivateKeyPasswordsTable_CleanupSecretsHardDeletedPrivateKeysSuccess()
    {
        AccountRecoveryPrivateKeyPasswordFactory::make()->persist();

        $this->assertEquals(0, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords', 'cleanupHardDeletedAccountRecoveryPrivateKeys', 0);
    }
}
