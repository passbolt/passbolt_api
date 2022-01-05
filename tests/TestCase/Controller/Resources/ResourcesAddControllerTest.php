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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceTypeFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthTestTrait;

class ResourcesAddControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use JwtAuthTestTrait;
    use ResourcesModelTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    public $Secrets;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    public function setUp(): void
    {
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources->getEventManager()->setEventList(new EventList());
        ResourceTypeFactory::make()->default()->persist();
        (new JwtKeyPairService())->createKeyPair();
        $this->enableFeaturePlugin('JwtAuthentication');
        $this->setEmailNotificationsSetting('password.create', true);
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
        $this->restoreEmailNotificationsSettings();
        unset($this->Resources);
        unset($this->Permissions);
        unset($this->Resources);
    }

    public function testResourcesAddSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json?api-version=2', $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals($user->id, $resource->created_by);
        $this->assertEquals($user->id, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($user->id, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($user->id, $resource->modifier->id);

        // Check the permission attribute
        $this->assertNotNull($resource->permission);
        $this->assertPermissionAttributes($resource->permission);
        $this->assertEquals('Resource', $resource->permission->aco);
        $this->assertEquals($resource->id, $resource->permission->aco_foreign_key);
        $this->assertEquals('User', $resource->permission->aro);
        $this->assertEquals($user->id, $resource->permission->aro_foreign_key);
        $this->assertEquals(Permission::OWNER, $resource->permission->type);

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
        $this->assertCount(1, $resource->secrets);
        $this->assertEquals($user->id, $resource->secrets[0]->user_id);
        $this->assertEquals($resource->id, $resource->secrets[0]->resource_id);
        $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);

        // Ensure that an email was sent
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'You added the password ' . $data['name'],
            'template' => ResourceCreateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailQueueCount(1);

        $this->assertEventFired(ResourcesAddService::ADD_SUCCESS_EVENT_NAME, $this->Resources->getEventManager());
    }

    public function testResourcesAddSuccessWithJWT()
    {
        $user = UserFactory::make()->user()->persist();
        $this->createJwtTokenAndSetInHeader($user->id);

        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json?api-version=2', $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals($user->id, $resource->created_by);
        $this->assertEquals($user->id, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($user->id, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($user->id, $resource->modifier->id);

        // Check the permission attribute
        $this->assertNotNull($resource->permission);
        $this->assertPermissionAttributes($resource->permission);
        $this->assertEquals('Resource', $resource->permission->aco);
        $this->assertEquals($resource->id, $resource->permission->aco_foreign_key);
        $this->assertEquals('User', $resource->permission->aro);
        $this->assertEquals($user->id, $resource->permission->aro_foreign_key);
        $this->assertEquals(Permission::OWNER, $resource->permission->type);

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
        $this->assertCount(1, $resource->secrets);
        $this->assertEquals($user->id, $resource->secrets[0]->user_id);
        $this->assertEquals($resource->id, $resource->secrets[0]->resource_id);
        $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);

        // Ensure that an email was sent
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'You added the password ' . $data['name'],
            'template' => ResourceCreateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailQueueCount(1);

        $this->assertEventFired(ResourcesAddService::ADD_SUCCESS_EVENT_NAME, $this->Resources->getEventManager());
    }

    public function testResourcesAddCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $data = $this->getDummyResourcesPostData();
        $this->post('/resources.json', $data);
        $this->assertResponseCode(403);
        // This will throw a route not found exeption
        $data = $this->_getBodyAsString();
        $expect = 'Missing or incorrect CSRF cookie type.';
        $this->assertStringContainsString($expect, $data);
    }

    /**
     * @dataProvider dataForTestResourcesAddValidationErrors
     * @dataProvider dataForTestResourcesAddBuildRulesErrors
     */
    public function testResourcesAddValidationErrors(string $caseLabel, array $case)
    {
        $this->logInAsUser();
        $this->postJson('/resources.json?api-version=v2', $case['data']);
        $this->assertError(400, 'Could not validate resource data');
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, $case['errorField']);
        $this->assertNotNull($error, "The case \"$caseLabel\" should fail");
        $this->assertSame(0, $this->Resources->find()->count());
        $this->assertSame(0, $this->Secrets->find()->count());
        $this->assertSame(0, $this->Permissions->find()->count());
        $this->assertEmailQueueIsEmpty();
    }

    public function dataForTestResourcesAddValidationErrors(): array
    {
        return [
            ['resource name is missing', [
                'errorField' => 'name._empty',
                'data' => $this->getDummyResourcesPostData(['name' => null]),
            ]],
            ['secret must be provided', [
                'errorField' => 'secrets._empty',
                'data' => $this->getDummyResourcesPostData(['secrets' => null]),
            ]],
            ['secret data must be provided', [
                'errorField' => 'secrets.0.data._required',
                'data' => $this->getDummyResourcesPostData(['secrets' => []]),
            ]],
            ['secret is invalid', [
                'errorField' => 'secrets.0.data.isValidGpgMessage',
                'data' => $this->getDummyResourcesPostData(['secrets' => [
                    0 => ['data' => 'Invalid secret'],
                ]]),
            ]],
            ['too many secrets provided', [
                'errorField' => 'secrets.hasAtMost',
                'data' => $this->getDummyResourcesPostData(['secrets' => [
                    0 => ['data' => $this->getDummyGpgMessage()],
                    1 => ['user_id' => UuidFactory::uuid('user.id.betty'), 'data' => $this->getDummyGpgMessage()],
                ]]),
            ]],
            ['invalid resource type', [
                'errorField' => 'resource_type_id',
                'data' => $this->getDummyResourcesPostData([
                    'name' => 'new resource name',
                    'username' => 'username@domain.com',
                    'uri' => 'https://www.domain.com',
                    'description' => 'new resource description',
                    'resource_type_id' => 'invalid',
                ]),
            ]],
        ];
    }

    public function dataForTestResourcesAddBuildRulesErrors(): array
    {
        return [
            ['non-existing resource type', [
                'errorField' => 'resource_type_id',
                'data' => $this->getDummyResourcesPostData([
                    'name' => 'new resource name',
                    'username' => 'username@domain.com',
                    'uri' => 'https://www.domain.com',
                    'description' => 'new resource description',
                    'resource_type_id' => UuidFactory::uuid(),
                ]),
            ]],
        ];
    }

    public function testResourcesAddNonValidUserUuid()
    {
        $user = UserFactory::make(['id' => 'Not a valid UUID'])->getEntity();
        $this->logInAs($user);
        $this->postJson('/resources.json?api-version=v2');
        $this->assertResponseFailure('The user identifier should be a valid UUID.');
    }

    public function testResourcesAddErrorNotAuthenticated()
    {
        $this->postJson('/resources.json?api-version=v2');
        $this->assertAuthenticationError();
    }
}
