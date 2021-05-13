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
 * @since         3.3.0
 */

namespace Passbolt\Mobile\Test\TestCase\Model\Table\Transfers;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;
    use TransfersModelTrait;

    public $Transfers;

    public $fixtures = [
        'app.Base/Users',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->Transfers = TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers');
    }

    public function tearDown(): void
    {
        unset($this->Transfers);
        parent::tearDown();
    }

    public function getEntityDefaultOptions()
    {
        $entityOptions = $this->getTransferEntityAccessibleFields();

        return $entityOptions;
    }

    public function runTestsForField(string $fieldName, array $testCases)
    {
        $this->assertFieldFormatValidation(
            $this->Transfers,
            $fieldName,
            $this->getDummyTransfer(),
            $this->getEntityDefaultOptions(),
            $testCases
        );
    }

    /* FORMAT VALIDATION TESTS */

    public function testMobileTransfersModel_ValidateId()
    {
        $this->runTestsForField('id', [
            'uuid' => self::getUuidTestCases(),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ]);
    }

    public function testMobileTransfersModel_ValidateUserId()
    {
        $this->runTestsForField('user_id', [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            //'notEmpty' => self::getNotEmptyTestCases(),
        ]);
    }

    public function testMobileTransfersModel_ValidateHash()
    {
        $this->runTestsForField('hash', [
            'ascii' => self::getAsciiTestCases(Transfer::TRANSFER_HASH_SIZE),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'lengthBetween' => [
                'rule_name' => 'lengthBetween',
                'test_cases' => [
                    self::getStringMask('alphaASCII', Transfer::TRANSFER_HASH_SIZE) => true,
                    self::getStringMask('alphaASCII', Transfer::TRANSFER_HASH_SIZE - 1) => false,
                    self::getStringMask('alphaASCII', Transfer::TRANSFER_HASH_SIZE + 1) => false,
                ],
            ],
            //'notEmpty' => self::getNotEmptyTestCases(),
        ]);
    }

    public function testMobileTransfersModel_ValidateCurrentPage()
    {
        $this->markTestIncomplete();
    }

    public function testMobileTransfersModel_ValidateTotalPage()
    {
        $this->markTestIncomplete();
    }

    public function testMobileTransfersModel_ValidateStatus()
    {
        $this->markTestIncomplete();
    }
}
