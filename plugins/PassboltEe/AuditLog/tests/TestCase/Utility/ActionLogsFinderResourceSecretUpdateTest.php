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
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\AuditLog\Test\Lib\ActionLogsOperationsTestTrait;
use Passbolt\AuditLog\Utility\ActionLogResultsParser;
use Passbolt\AuditLog\Utility\ResourceActionLogsFinder;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsFactory;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\Log\Model\Table\PermissionsHistoryTable&\Cake\ORM\Association\BelongsTo $PermissionsHistory
 */
class ActionLogsFinderResourceSecretUpdateTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTestTrait;
    use UserAccessControlTrait;

    public array $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Resources',
        'app.Base/ResourceTypes',
        'app.Base/Permissions',
    ];

    public function testAuditLogsActionLogsFinderResourceSecretUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateResourceSecretUpdate($uac, $resourceId);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resource.Secrets.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['secrets']));
    }

    public function testAuditLogsActionLogsFinderResourceSecretUpdate_WithSecretRevision(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);
        $resourceWithLegacyFormat = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withSecretsFor([$user])
            ->persist();
        /**
         * With legacy secret history scenario
         */
        $this->simulateResourceSecretUpdate($uac, $resourceWithLegacyFormat->get('id'));
        /**
         * Secret revision scenario - new format
         */
        $resourceWithNewFormat = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withSecretsFor([$user])
            ->persist();
        $resourceNewFormatId = $resourceWithNewFormat->get('id');
        $actionLogNewFormat = ActionLogFactory::make()
            ->with('Actions', ActionFactory::make(['name' => 'ResourcesUpdate.update']))
            ->userId($user->get('id'))
            ->success()
            ->persist();
        // entities history - new format
        EntitiesHistoryFactory::make()
            ->withActionLog($actionLogNewFormat)
            ->withResource($resourceWithNewFormat)
            ->update()
            ->persist();
        $secretRevision = SecretRevisionsFactory::make([
            'resource_id' => $resourceNewFormatId,
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ])->persist();
        EntitiesHistoryFactory::make()
            ->withActionLog($actionLogNewFormat)
            ->withSecretRevisions($secretRevision)
            ->create()
            ->persist();

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceNewFormatId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceNewFormatId]]))->parse();

        $this->assertEquals(2, count($actionLogs));
        $this->assertEquals('Resources.updated', $actionLogs[0]['type']);
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        // Assert secret revision
        $this->assertEquals(ActionLogResultsParser::TYPE_SECRETS_UPDATED, $actionLogs[1]['type']);
        $this->assertTrue(isset($actionLogs[1]['data']));
        $creator = $actionLogs[1]['creator'];
        $this->assertSame($uac->getId(), $creator['id']);
        $this->assertSame($uac->getUsername(), $creator['username']);
        $this->assertNotEmpty($creator['profile']['first_name']);
        $this->assertNotEmpty($creator['profile']['last_name']);
    }
}
