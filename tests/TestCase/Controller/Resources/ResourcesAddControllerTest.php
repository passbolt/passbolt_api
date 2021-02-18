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
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResourcesAddControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Profiles',
        'app.Base/Secrets', 'app.Base/Permissions', 'app.Base/Roles', 'app.Base/Avatars', 'app.Base/Favorites',
         'app.Base/ResourceTypes',
    ];

    public function setUp()
    {
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        parent::setUp();
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

    protected function _getDummyPostData($data = [])
    {
        $defaultData = [
            'name' => 'new resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'new resource description',
            'secrets' => [
                [
                    'data' => $this->_getGpgMessage(),
                ],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testResourcesAddSuccess()
    {
        $success = [
            'chinese' => $this->_getDummyPostData([
                'name' => '新的專用資源名稱',
                'username' => 'username@domain.com',
                'uri' => 'https://www.域.com',
                'description' => '新的資源描述',
            ]),
            'slavic' => $this->_getDummyPostData([
                'name' => 'Новое имя частного ресурса',
                'username' => 'username@domain.com',
                'uri' => 'https://www.домен.com',
                'description' => 'Новое описание частного ресурса',
            ]),
            'french' => $this->_getDummyPostData([
                'name' => 'Nouveau nom de resource privée',
                'username' => 'username@domain.com',
                'uri' => 'https://www.mon-domain.com',
                'description' => 'Nouvelle description de resource privée',
            ]),
            'emoticon' => $this->_getDummyPostData([
                'name' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
            ]),
        ];

        foreach ($success as $case => $data) {
            $userId = UuidFactory::uuid('user.id.ada');
            $this->authenticateAs('ada');
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
            $this->assertEquals($userId, $resource->created_by);
            $this->assertEquals($userId, $resource->modified_by);

            // Check the creator attribute
            $this->assertNotNull($resource->creator);
            $this->assertUserAttributes($resource->creator);
            $this->assertEquals($userId, $resource->creator->id);

            // Check the modifier attribute
            $this->assertNotNull($resource->modifier);
            $this->assertUserAttributes($resource->modifier);
            $this->assertEquals($userId, $resource->modifier->id);

            // Check the permission attribute
            $this->assertNotNull($resource->permission);
            $this->assertPermissionAttributes($resource->permission);
            $this->assertEquals('Resource', $resource->permission->aco);
            $this->assertEquals($resource->id, $resource->permission->aco_foreign_key);
            $this->assertEquals('User', $resource->permission->aro);
            $this->assertEquals($userId, $resource->permission->aro_foreign_key);
            $this->assertEquals(Permission::OWNER, $resource->permission->type);

            // Check the secret attribute
            $this->assertNotEmpty($resource->secrets);
            $this->assertSecretAttributes($resource->secrets[0]);
            $this->assertCount(1, $resource->secrets);
            $this->assertEquals($userId, $resource->secrets[0]->user_id);
            $this->assertEquals($resource->id, $resource->secrets[0]->resource_id);
            $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);
        }
    }

    public function testResourcesAddCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $data = $this->_getDummyPostData();
        $this->post('/resources.json', $data);
        $this->assertResponseCode(403);
        $data = $this->_getBodyAsString();
        $expect = 'Missing CSRF token cookie';
        $this->assertContains($expect, $data);
    }

    public function testResourcesAddValidationErrors()
    {
        $responseCode = 400;
        $responseMessage = 'Could not validate resource data';
        $errors = [
            'resource name is missing' => [
                'errorField' => 'name._empty',
                'data' => $this->_getDummyPostData(['name' => null]),
            ],
            'secret must be provided' => [
                'errorField' => 'secrets._empty',
                'data' => $this->_getDummyPostData(['secrets' => null]),
            ],
            'secret data must be provided' => [
                'errorField' => 'secrets.0.data._required',
                'data' => $this->_getDummyPostData(['secrets' => []]),
            ],
            'secret is invalid' => [
                'errorField' => 'secrets.0.data.isValidGpgMessage',
                'data' => $this->_getDummyPostData(['secrets' => [
                    0 => ['data' => 'Invalid secret'],
                ]]),
            ],
            'too many secrets provided' => [
                'errorField' => 'secrets.hasAtMost',
                'data' => $this->_getDummyPostData(['secrets' => [
                    0 => ['data' => $this->_getGpgMessage()],
                    1 => ['user_id' => UuidFactory::uuid('user.id.betty'), 'data' => $this->_getGpgMessage()],
                ]]),
            ],
            'invalid resource type' => [
                'errorField' => 'resource_type_id',
                'data' => $this->_getDummyPostData([
                    'name' => 'new resource name',
                    'username' => 'username@domain.com',
                    'uri' => 'https://www.domain.com',
                    'description' => 'new resource description',
                    'resource_type_id' => 'invalid',
                ]),
            ],
            'non-existing resource type' => [
                'errorField' => 'resource_type_id',
                'data' => $this->_getDummyPostData([
                    'name' => 'new resource name',
                    'username' => 'username@domain.com',
                    'uri' => 'https://www.domain.com',
                    'description' => 'new resource description',
                    'resource_type_id' => UuidFactory::uuid(),
                ]),
            ],
        ];

        foreach ($errors as $caseLabel => $case) {
            $this->authenticateAs('ada');
            $this->postJson('/resources.json?api-version=v2', $case['data']);
            $this->assertError($responseCode, $responseMessage);
            $arr = json_decode(json_encode($this->_responseJsonBody), true);
            $error = Hash::get($arr, $case['errorField']);
            $this->assertNotNull($error, "The case \"$caseLabel\" should fail");
            $this->assertResourceNotExist(['name' => $case['data']['name']]);
        }
    }

    public function testResourcesAddErrorNotAuthenticated()
    {
        $data = $this->_getDummyPostData();
        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertAuthenticationError();
    }
}
