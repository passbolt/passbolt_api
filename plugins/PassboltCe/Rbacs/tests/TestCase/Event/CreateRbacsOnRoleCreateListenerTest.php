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

namespace Passbolt\Rbacs\Test\TestCase\Event;

use App\Model\Entity\Role;
use App\Service\Roles\RolesAddService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\Event\Event;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Event\CreateRbacsOnRoleCreateListener;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;

/**
 * @covers \Passbolt\Rbacs\Event\CreateRbacsOnRoleCreateListener
 */
class CreateRbacsOnRoleCreateListenerTest extends AppTestCaseV5
{
    /**
     * @var \Passbolt\Rbacs\Event\CreateRbacsOnRoleCreateListener
     */
    private CreateRbacsOnRoleCreateListener $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new CreateRbacsOnRoleCreateListener();
        // populate default roles
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->admin()->persist();
        RoleFactory::make()->user()->persist();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testCreateRbacsOnRoleCreateListener(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);
        // Populate Rbacs for testing
        // for user
        $userRoleId = RoleFactory::find()->where(['name' => Role::USER])->firstOrFail()->get('id');
        RbacFactory::make(['role_id' => $userRoleId])
            ->user()
            ->setUiAction(UiActionFactory::make()->name(UiAction::NAME_RESOURCES_IMPORT)->persist())
            ->persist();
        RbacFactory::make(['role_id' => $userRoleId])
            ->user()
            ->setUiAction(UiActionFactory::make()->name(UiAction::NAME_SECRETS_COPY)->persist())
            ->persist();
        RbacFactory::make(['role_id' => $userRoleId])
            ->user()
            ->setAction(ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_GROUPS_ADD)->persist())
            ->persist();
        RbacFactory::make(['role_id' => $userRoleId])
            ->user()
            ->setAction(ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW)->persist())
            ->persist();
        // for admin
        $adminRoleId = RoleFactory::find()->where(['name' => Role::ADMIN])->firstOrFail()->get('id');
        RbacFactory::make(['role_id' => $adminRoleId])
            ->user()
            ->setUiAction(UiActionFactory::make()->name(UiAction::NAME_IN_FORM_MENU_USE)->persist())
            ->persist();
        RbacFactory::make(['role_id' => $adminRoleId])
            ->user()
            ->setAction(ActionFactory::make()->name(RbacsControlledActionsInsertService::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE)->persist())
            ->persist();

        // Mimic create
        $role = (new RolesAddService())->add($uac, ['name' => 'developers']);
        $eventData = [
            'role' => $role,
            'uac' => $uac,
        ];
        // Prepare event
        $event = new Event(RolesAddService::AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME);
        $event->setData($eventData);
        $this->sut->createRbacsOnRoleCreate($event);

        $userRbacEntriesCount = RbacFactory::find()->where(['role_id' => $userRoleId])->all()->count();
        /** @var \Passbolt\Rbacs\Model\Entity\Rbac[] $roleCreatedRbacEntries */
        $roleCreatedRbacEntries = RbacFactory::find()->where(['role_id' => $role->id])->all()->toArray();
        $roleCreatedRbacEntriesCount = count($roleCreatedRbacEntries);
        $this->assertGreaterThan(0, $roleCreatedRbacEntriesCount);
        $this->assertSame($userRbacEntriesCount, $roleCreatedRbacEntriesCount);
        $rbac1 = $roleCreatedRbacEntries[0];
        $this->assertSame($uac->getId(), $rbac1->created_by);
        $this->assertSame($uac->getId(), $rbac1->modified_by);
    }
}
