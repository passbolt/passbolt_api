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
 * @since         2.0.0
 */

namespace Passbolt\Log\Test\TestCase\Controller\Share;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Passbolt\Log\Events\ActionsListener;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\TestCase\Model\Traits\EntitiesHistoryTrait;
use Passbolt\Log\Test\TestCase\Model\Traits\ActionLogsTrait;

class ResourcesControllerLogTest extends AppIntegrationTestCase
{
    use EntitiesHistoryTrait;
    use ActionLogsTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/gpgkeys', 'app.Base/profiles', 'app.Base/avatars', 'app.Base/roles', 'app.Base/groups',
        'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions', 'app.Base/secrets', 'app.Base/secret_accesses', 'app.Base/favorites','app.Base/email_queue',
        'plugin.passbolt/log.Base/actions', 'plugin.passbolt/log.Base/actionLogs', 'plugin.passbolt/log.Base/entitiesHistory', 'plugin.passbolt/log.Base/permissionsHistory',
        'plugin.passbolt/log.Base/secretsHistory'
    ];

    public function setUp()
    {
        $this->Resources = TableRegistry::get('Resources');
        $this->Users = TableRegistry::get('Users');
        $this->ActionLogs = TableRegistry::get('Passbolt/Log.ActionLogs');
        $this->EntitiesHistory = TableRegistry::get('Passbolt/Log.EntitiesHistory');
        parent::setUp();

        $this->initActionLogEvents();
    }

    protected function initActionLogEvents()
    {
        UserAction::destroy();
        $actions = new ActionsListener();
        EventManager::instance()->on($actions);
    }

    protected function _getDummyPostData($data = [])
    {
        $defaultData = [
            'Resource' => [
                'name' => 'new resource name',
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => 'new resource description'
            ],
            'Secret' => [
                [
                    'data' => $this->_getGpgMessage()
                ]
            ]
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    protected function _getCombinedDummyPostData($resource = null, $data = [])
    {
        // Build the default data
        $defaultData = [
            'name' => 'Resource name updated by test',
            'username' => 'username_updated@by.test',
            'uri' => 'https://uri.updated.by.test',
            'description' => 'Resource description updated'
        ];

        // If secrets provided update them all.
        if (isset($resource->secrets)) {
            foreach ($resource->secrets as $secret) {
                $defaultData['secrets'][] = [
                    'id' => $secret->id,
                    'user_id' => $secret->user_id,
                    'data' => $this->_getGpgMessage()
                ];
            }
        }

        $data = array_merge($defaultData, $data);

        return $data;
    }

    protected function _getGpgMessage()
    {
        return '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----';
    }

    public function testAddSuccessWithSecrets()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $data = $this->_getDummyPostData();
        $this->postJson("/resources.json", $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('ResourcesAdd.add'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert entityHistory is correct.
        $expectedEntityHistory = [
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'Resources',
            'foreign_key' => $resource->Resource->id,
            'crud' => EntityHistory::CRUD_CREATE,
        ];
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists($expectedEntityHistory);
    }

    public function testUpdateSuccessWithoutSecrets()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        $data = $this->_getCombinedDummyPostData($resource, ['name' => 'nameupdated']);
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
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

    public function testUpdateSuccessWithSecrets()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getCombinedDummyPostData($resource, ['name' => 'nameupdated']);
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
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

    public function testDeleteSuccess()
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