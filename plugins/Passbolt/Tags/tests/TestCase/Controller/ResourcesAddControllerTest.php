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
namespace Passbolt\Tags\Test\TestCase\Controller;

use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourceAddControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/avatars', 'app.Base/roles',
        'app.Base/groups', 'app.Alt0/groups_users',
        'app.Base/resources', 'app.Base/secrets', 'app.Base/favorites', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags',
        'app.Base/email_queue'];

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
                    'data' => '-----BEGIN PGP MESSAGE-----

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
-----END PGP MESSAGE-----'
                ]
            ]
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testResourcesAddSuccess()
    {
        $success = [
            'resource' => $this->_getDummyPostData()
        ];

        foreach ($success as $case => $data) {
            $this->authenticateAs('admin');
            $this->postJson("/resources.json?api-version=2", $data);
            $this->assertSuccess();

            // Check the server response.
            $resource = $this->_responseJsonBody;

            // Check the resource attributes.
            $this->assertObjectHasAttribute('tags', $resource);
            $this->assertTrue(is_array($resource->tags));
        }
    }
}
