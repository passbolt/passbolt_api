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

use App\Test\Factory\RoleFactory;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
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

    public function testRbacsTable_BuildRules_AllowedControlFunctionForUiAction_Valid(): void
    {
        $userRole = RoleFactory::make()->user()->persist();
        $uiAction = UiActionFactory::make()->name(UiAction::NAME_RESOURCES_IMPORT)->persist();
        $data = [
            'role_id' => $userRole->get('id'),
            'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
            'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
            'foreign_id' => $uiAction->get('id'),
        ];
        $rbac = $this->Rbacs->newEntity($data, [
            'accessibleFields' => [
                'role_id' => true,
                'control_function' => true,
                'foreign_model' => true,
                'foreign_id' => true,
            ],
        ]);

        $result = $this->Rbacs->save($rbac);

        $this->assertInstanceOf(Rbac::class, $result);
    }

    public function testRbacsTable_BuildRules_AllowedControlFunctionForAction_Valid(): void
    {
        $role = RoleFactory::make()->persist();
        $action = ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_GROUPS_ADD)->persist();

        $data = [
            'role_id' => $role->get('id'),
            'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
            'foreign_model' => Rbac::FOREIGN_MODEL_ACTION,
            'foreign_id' => $action->get('id'),
        ];
        $rbac = $this->Rbacs->newEntity($data, [
            'accessibleFields' => [
                'role_id' => true,
                'control_function' => true,
                'foreign_model' => true,
                'foreign_id' => true,
            ],
        ]);

        $result = $this->Rbacs->save($rbac);

        $this->assertInstanceOf(Rbac::class, $result);
    }

    public function testRbacsTable_BuildRules_AllowedControlFunctionForAction_Invalid(): void
    {
        $role = RoleFactory::make()->persist();

        $data = [
            'role_id' => $role->get('id'),
            'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
            'foreign_model' => Rbac::FOREIGN_MODEL_ACTION,
            'foreign_id' => UuidFactory::uuid(),
        ];
        $rbac = $this->Rbacs->newEntity($data, [
            'accessibleFields' => [
                'role_id' => true,
                'control_function' => true,
                'foreign_model' => true,
                'foreign_id' => true,
            ],
        ]);

        $result = $this->Rbacs->save($rbac);

        $this->assertFalse($result);
        $this->assertSame(
            'The control function is not allowed for this Action.',
            $rbac->getErrors()['control_function']['isControlFunctionAllowed']
        );
    }

    public function testRbacsTable_BuildRules_AllowedControlFunctionForUiAction_Invalid(): void
    {
        $userRole = RoleFactory::make()->user()->persist();
        $uiAction = UiActionFactory::make()->name(UiAction::NAME_RESOURCES_IMPORT)->persist();
        $data = [
            'role_id' => $userRole->get('id'),
            'control_function' => Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
            'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
            'foreign_id' => $uiAction->get('id'),
        ];
        $rbac = $this->Rbacs->newEntity($data, [
            'accessibleFields' => [
                'role_id' => true,
                'control_function' => true,
                'foreign_model' => true,
                'foreign_id' => true,
            ],
        ]);

        $result = $this->Rbacs->save($rbac);

        $this->assertFalse($result);
        $errors = $rbac->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('isControlFunctionAllowed', $errors['control_function']);
    }

    public function testRbacsTable_BuildRules_AllowedControlFunctionForUiAction_Invalid_ForeignIdNotPresent(): void
    {
        $userRole = RoleFactory::make()->user()->persist();
        UiActionFactory::make()->name(UiAction::NAME_RESOURCES_IMPORT)->persist();
        $data = [
            'role_id' => $userRole->get('id'),
            'control_function' => Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
            'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
            'foreign_id' => UuidFactory::uuid(),
        ];
        $rbac = $this->Rbacs->newEntity($data, [
            'accessibleFields' => [
                'role_id' => true,
                'control_function' => true,
                'foreign_model' => true,
                'foreign_id' => true,
            ],
        ]);

        $result = $this->Rbacs->save($rbac);

        $this->assertFalse($result);
        $errors = $rbac->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('isControlFunctionAllowed', $errors['control_function']);
    }

    public function testRbacsTable_isActionAllowedForRole(): void
    {
        $actionId = UuidFactory::uuid();
        $roleId = UuidFactory::uuid();
        $factory = RbacFactory::make();

        $factory->persist();
        $this->assertFalse($this->Rbacs->isActionAllowedForRole($roleId, $actionId));

        $factory->setField('foreign_id', $actionId)->persist();
        $this->assertFalse($this->Rbacs->isActionAllowedForRole($roleId, $actionId));

        $factory->setField('foreign_model', Rbac::FOREIGN_MODEL_ACTION)->persist();
        $this->assertFalse($this->Rbacs->isActionAllowedForRole($roleId, $actionId));

        $factory->setField('role_id', $roleId)->persist();
        $this->assertTrue($this->Rbacs->isActionAllowedForRole($roleId, $actionId));
    }
}
