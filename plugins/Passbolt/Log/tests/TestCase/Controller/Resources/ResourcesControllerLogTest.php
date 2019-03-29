<?php
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
 * @since         2.8.0
 */

namespace Passbolt\Log\Test\TestCase\Controller\Share;

use App\Utility\UuidFactory;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class ResourcesControllerLogTest extends LogIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Roles',
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Secrets',
        'plugin.Passbolt/Log.Base/SecretAccesses', 'app.Base/Favorites', 'app.Base/EmailQueue',
        'plugin.Passbolt/Log.Base/Actions', 'plugin.Passbolt/Log.Base/ActionLogs',
        'plugin.Passbolt/Log.Base/EntitiesHistory', 'plugin.Passbolt/Log.Base/PermissionsHistory',
        'plugin.Passbolt/Log.Base/SecretsHistory'
    ];

    public function testLogResourcesAddSuccessWithSecrets()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $data = [
            'name' => 'new resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'new resource description',
            'secrets' => [[
                'data' => self::getDummySecretData($userId)
            ]]
        ];
        $this->postJson("/resources.json?api-version=v2", $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesAdd.add'),
            'user_id' => $userId,
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert entityHistory is correct.
        $expectedEntityHistory = [
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'Resources',
            'foreign_key' => $resource->id,
            'crud' => EntityHistory::CRUD_CREATE,
        ];
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists($expectedEntityHistory);
    }

    public function testLogResourcesUpdateSuccessWithoutSecrets()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = [
            'id' => $resourceId,
            'name' => 'updated name',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache description udpated.'
        ];
        $this->putJson("/resources/$resourceId.json?api-version=2", $resource);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesUpdate.update'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert entityHistory is correct.
        $expectedEntityHistory = [
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'Resources',
            'foreign_key' => $resourceId,
            'crud' => EntityHistory::CRUD_UPDATE,
        ];
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists($expectedEntityHistory);
    }

    public function testLogResourcesUpdateSuccessWithSecrets()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $adaId = UuidFactory::uuid('user.id.ada');
        $bettyId = UuidFactory::uuid('user.id.betty');
        $carolId = UuidFactory::uuid('user.id.carol');
        $dameId = UuidFactory::uuid('user.id.dame');
        $resource = [
            'id' => UuidFactory::uuid('resource.id.apache'),
            'name' => 'updated name',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache description udpated.',
            'secrets' => [[
                'id' => UuidFactory::uuid("secret.id.$resourceId-$adaId"),
                'data' => self::getDummySecretData($adaId),
                'user_id' => $adaId
            ], [
                'id' => UuidFactory::uuid("secret.id.$resourceId-$bettyId"),
                'data' => self::getDummySecretData($bettyId),
                'user_id' => $bettyId
            ], [
                'id' => UuidFactory::uuid("secret.id.$resourceId-$carolId"),
                'data' => self::getDummySecretData($carolId),
                'user_id' => $carolId
            ], [
                'id' => UuidFactory::uuid("secret.id.$resourceId-$dameId"),
                'data' => self::getDummySecretData($dameId),
                'user_id' => $dameId
            ]]
        ];
        $this->putJson("/resources/$resourceId.json?api-version=2", $resource);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesUpdate.update'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert entityHistory is correct.
        $expectedEntityHistory = [
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'Resources',
            'foreign_key' => $resourceId,
            'crud' => EntityHistory::CRUD_UPDATE,
        ];
        $this->assertOneEntityHistory(['foreign_model' => 'Resources']);
        $this->assertEntitiesHistoryCount('4', ['foreign_model' => 'SecretsHistory']);
        $this->assertEntityHistoryExists($expectedEntityHistory);
    }

    public function testLogResourcesDeleteSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();

        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesDelete.delete'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert entityHistory is correct.
        $expectedEntityHistory = [
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'Resources',
            'foreign_key' => $resourceId,
            'crud' => EntityHistory::CRUD_DELETE,
        ];
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists($expectedEntityHistory);
    }
}
