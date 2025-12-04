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
 * @since         5.8.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Service\Rbacs;

use App\Test\Factory\RoleFactory;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Service\Rbacs\InsertRbacsForActionsService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class InsertRbacsForActionsServiceTest extends RbacsTestCase
{
    private ?InsertRbacsForActionsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new InsertRbacsForActionsService();
    }

    public function testInsertRbacsForActionsService(): void
    {
        $guestRole = RoleFactory::make()->guest()->persist();
        $adminRole = RoleFactory::make()->admin()->persist();
        $userRole = RoleFactory::make()->user()->persist();
        $customRole = RoleFactory::make(['name' => 'marketing'])->persist();
        // actions
        $fixtureActions = [
            RbacsControlledActionsInsertService::NAME_GROUPS_ADD,
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW,
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE,
        ];
        foreach ($fixtureActions as $fixtureAction) {
            ActionFactory::make()->name($fixtureAction)->persist();
        }
        // Rbacs
        RbacFactory::make(['role_id' => $userRole->id])
            ->setUiAction(UiActionFactory::make()->persist())
            ->allow()
            ->persist();
        RbacFactory::make(['role_id' => $customRole->get('id')])
            ->setUiAction(UiActionFactory::make()->persist())
            ->allow()
            ->persist();
        RbacFactory::make(['role_id' => $userRole->id])->setUiAction(UiActionFactory::make()->persist())->deny()->persist();
        RbacFactory::make(['role_id' => $customRole->get('id')])->setUiAction(UiActionFactory::make()->persist())->deny()->persist();

        $result = $this->service->add([
            RbacsControlledActionsInsertService::NAME_GROUPS_ADD,
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW,
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE,
        ]);

        $this->assertSame(6, $result);
        $this->assertSame(
            0,
            RbacFactory::find()
                ->where(['role_id' => $guestRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION])
                ->count()
        );
        $this->assertSame(
            0,
            RbacFactory::find()
                ->where(['role_id' => $adminRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION])
                ->count()
        );
        $this->assertSame(
            3,
            RbacFactory::find()
                ->where(['role_id' => $userRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION, 'control_function' => Rbac::CONTROL_FUNCTION_DENY])
                ->count()
        );
        $this->assertSame(
            3,
            RbacFactory::find()
                ->where(['role_id' => $customRole->get('id'), 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION, 'control_function' => Rbac::CONTROL_FUNCTION_DENY])
                ->count()
        );
    }

    public function testInsertRbacsForActionsService_FewRbacsAlreadyPresent(): void
    {
        $guestRole = RoleFactory::make()->guest()->persist();
        $adminRole = RoleFactory::make()->admin()->persist();
        $userRole = RoleFactory::make()->user()->persist();
        $customRole = RoleFactory::make(['name' => 'marketing'])->persist();
        // Rbacs
        RbacFactory::make(['role_id' => $userRole->id])
            ->setUiAction(UiActionFactory::make()->persist())
            ->allow()
            ->persist();
        RbacFactory::make(['role_id' => $customRole->get('id')])
            ->setUiAction(UiActionFactory::make()->persist())
            ->allow()
            ->persist();
        RbacFactory::make(['role_id' => $userRole->id])->setUiAction(UiActionFactory::make()->persist())->deny()->persist();
        RbacFactory::make(['role_id' => $customRole->get('id')])->setUiAction(UiActionFactory::make()->persist())->deny()->persist();
        // Populate some already
        ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE)->persist();
        $action = ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW)->persist();
        RbacFactory::make(['role_id' => $userRole->id])->setAction($action)->allow()->persist();
        RbacFactory::make(['role_id' => $customRole->get('id')])->setAction($action)->allow()->persist();

        $result = $this->service->add([
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW,
            RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE,
        ]);

        $this->assertSame(2, $result);
        $this->assertSame(
            0,
            RbacFactory::find()
                ->where(['role_id' => $guestRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION])
                ->count()
        );
        $this->assertSame(
            0,
            RbacFactory::find()
                ->where(['role_id' => $adminRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION])
                ->count()
        );
        $this->assertSame(
            1,
            RbacFactory::find()
                ->where(['role_id' => $userRole->id, 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION, 'control_function' => Rbac::CONTROL_FUNCTION_DENY])
                ->count()
        );
        $this->assertSame(
            1,
            RbacFactory::find()
                ->where(['role_id' => $customRole->get('id'), 'foreign_model' => Rbac::FOREIGN_MODEL_ACTION, 'control_function' => Rbac::CONTROL_FUNCTION_DENY])
                ->count()
        );
    }
}
