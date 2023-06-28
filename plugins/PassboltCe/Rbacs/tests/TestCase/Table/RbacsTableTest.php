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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Table;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class RbacsTableTest extends RbacsTestCase
{
    use FormatValidationTrait;

    /**
     * @var \Passbolt\Rbacs\Model\Table\RbacsTable
     */
    public $Rbacs;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Rbacs = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
    }

    /**
     * Get default Rbacs entity options.
     */
    public function getDummyRbacsEntityDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    public function testRbacsTable_ValidationForeignModel(): void
    {
        $testCases = [
            'inList' => self::getInListTestCases(Rbac::ALLOWED_FOREIGN_MODELS),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'foreign_model', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }

    public function testRbacsTable_ValidationForeignId(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'foreign_id', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }

    public function testRbacsTable_ValidationControlFunction(): void
    {
        $testCases = [
            'inList' => self::getInListTestCases(Rbac::ALLOWED_CONTROL_FUNCTIONS),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'control_function', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }

    public function testRbacsTable_ValidationRoleId(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'role_id', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }

    public function testRbacsTable_ValidationCreatedBy(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'created_by', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }

    public function testRbacsTable_ValidationModifiedBy(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $data = RbacFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Rbacs, 'modified_by', $data, self::getDummyRbacsEntityDefaultOptions(), $testCases);
    }
}
