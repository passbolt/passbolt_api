<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Model\Entity\Role;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Passbolt\AuditLog\Test\Lib\ActionLogsOperationsTestTrait;
use Passbolt\AuditLog\Utility\ActionLogResultsParser;
use Passbolt\AuditLog\Utility\ResourceActionLogsFinder;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\Log\Model\Table\PermissionsHistoryTable&\Cake\ORM\Association\BelongsTo $PermissionsHistory
 */
class ActionLogsFinderResourcesCrudTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTestTrait;

    public function testAuditLogsActionLogsFinderResourcesCreated()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $this->simulateResourceCrud($uac, $resourceA->id, EntityHistory::CRUD_CREATE);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceA->id);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceA->id]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.created');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], $resourceA->name);
    }

    public function testAuditLogsActionLogsFinderResourcesUpdated()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $this->simulateResourceCrud($uac, $resourceA->id, EntityHistory::CRUD_UPDATE);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceA->id);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceA->id]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], $resourceA->name);
    }
}
