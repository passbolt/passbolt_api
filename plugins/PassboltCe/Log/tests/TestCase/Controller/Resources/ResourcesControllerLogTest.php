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
 * @since         2.8.0
 */

namespace Passbolt\Log\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesControllerLogTest extends LogIntegrationTestCase
{
    /**
     * @dataProvider dataProviderForLoginType
     */
    public function testLogResourcesAddSuccessWithSecrets(string $loginType)
    {
        $user = UserFactory::make()->user()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $this->loginWithDataProviderLoginTypeValue($loginType, $user);
        $data = [
            'name' => 'new resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'new resource description',
            'secrets' => [[
                'data' => SecretFactory::make()->getEntity()->data,//Hash::get(self::getDummySecretData(), 'data'),
            ]],
        ];
        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesAdd.add'),
            'user_id' => $user->id,
            'status' => 1,
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

    public function testLogResourcesAddSuccessWithSecretsErrorShouldHaveNoLogs()
    {
        $this->logInAsUser();
        $data = [
            'name' => 'new resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'new resource description',
            'resource_type_id' => UuidFactory::uuid(),
            'secrets' => [[
                'data' => Hash::get(self::getDummySecretData(), 'data'),
            ]],
        ];
        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertError(400, 'Could not validate resource data');
        $this->assertEntitiesHistoryEmpty();
    }

    public function testLogResourcesUpdateSuccessWithoutSecrets()
    {
        $user = $this->logInAsUser();
        ResourceTypeFactory::make()->default()->persist();
        RoleFactory::make()->guest()->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user])->persist()->get('id');

        $resource = [
            'id' => $resourceId,
            'name' => 'updated name',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache description udpated.',
        ];
        $this->putJson("/resources/$resourceId.json?api-version=2", $resource);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesUpdate.update'),
            'user_id' => $user->get('id'),
            'status' => 1,
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
        ResourceTypeFactory::make()->default()->persist();
        RoleFactory::make()->guest()->persist();
        /** @var \App\Model\Entity\User[] $users */
        $users = UserFactory::make(4)->persist();
        $this->logInAs($users[0]);

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor($users)
            ->withSecretsFor($users)
            ->persist();
        $resourceId = $resource->id;

        $resource = [
            'id' => $resourceId,
            'name' => 'updated name',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache description udpated.',
            'secrets' => [[
                'id' => $resource->secrets[0]->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'user_id' => $users[0]->id,
            ], [
                'id' => $resource->secrets[1]->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'user_id' => $users[1]->id,
            ], [
                'id' => $resource->secrets[2]->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'user_id' => $users[2]->id,
            ], [
                'id' => $resource->secrets[3]->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'user_id' => $users[3]->id,
            ]],
        ];
        $this->putJson("/resources/$resourceId.json?api-version=2", $resource);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesUpdate.update'),
            'user_id' => $users[0]->id,
            'status' => 1,
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
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $resourceId = $resource->id;
        $this->logInAs($user);

        $this->deleteJson("/resources/$resourceId.json");
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();

        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesDelete.delete'),
            'user_id' => $user->id,
            'status' => 1,
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
