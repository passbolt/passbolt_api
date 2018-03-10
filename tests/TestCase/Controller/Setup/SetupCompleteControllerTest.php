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
namespace App\Test\TestCase\Controller\Setup;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SetupCompleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles', 'app.Base/authentication_tokens'];
    public $AuthenticationTokens;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        $this->Users = TableRegistry::get('Users');
        $this->Gpgkeys = TableRegistry::get('Gpgkeys');
        parent::setUp();
    }

    public function testSetupCompleteApiV1Success()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ruth_public.key');
        $data = [
            'AuthenticationToken' => [
                'token' => $t->token
            ],
            'Gpgkey' => [
                'key' => $armoredKey
            ]
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();
        // Check that token is now inactive
        $t2 = $this->AuthenticationTokens->get($t->id);
        $this->assertFalse($t2->active);
        // Check that ruth is active
        $ruth = $this->Users->get(UuidFactory::uuid('user.id.ruth'));
        $this->assertTrue($ruth->active);
        // Check that ruth has a gpg key
        $key = $this->Gpgkeys->find()
            ->where(['user_id' => UuidFactory::uuid('user.id.ruth')])
            ->order('created')
            ->first();
        $this->assertTrue(!empty($key));
    }

    public function testSetupCompleteSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $t->token
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey
            ]
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();
    }

    public function testSetupCompleteInvalidUserIdError()
    {
        $url = '/setup/complete/nope.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user id is not valid.');
    }

    public function testSetupCompleteInvalidUserTokenError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.nope') . '.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user does not exist');
    }

    public function testSetupCompleteInvalidAuthenticationTokenError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';

        $fails = [
            'empty array' => [
                'data' => [],
                'message' => 'An authentication token must be provided.'
            ],
            'null' => [
                'data' => null,
                'message' => 'An authentication token must be provided.'
            ],
            'array with null' => [
                'data' => ['token' => null],
                'message' => 'An authentication token must be provided.'
            ],
            'int' => [
                'data' => ['token' => 100],
                'message' => 'The authentication token should be a valid uuid.'
            ],
            'string' => [
                'data' => ['token' => 'nope'],
                'message' => 'The authentication token should be a valid uuid.'
            ],
            'expired token' => [
                'data' => ['token' => UuidFactory::uuid('token.id.expired')],
                'message' => 'The authentication token is not valid or has expired.'
            ],
            'inactive token' => [
                'data' => ['token' => UuidFactory::uuid('token.id.inactive')],
                'message' => 'The authentication token is not valid or has expired.'
            ],
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
                'AuthenticationToken' => $case['data']
            ];
            $this->postJson($url, $data);
            $this->assertError(400, $case['message'], 'Issue with test case: ' . $caseName);
        }
    }

    public function testSetupCompleteInvalidGpgkeyError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'));
        $url = '/users/validateAccount/' . UuidFactory::uuid('user.id.ruth') . '.json';

        $armoredKey = file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ruth_public.key');
        $cutKey = substr($armoredKey, 0, strlen($armoredKey) / 2);
        $fails = [
            'empty array' => [
                'data' => [],
                'message' => 'An OpenPGP key must be provided.'
            ],
            'null' => [
                'data' => null,
                'message' => 'An OpenPGP key must be provided.'
            ],
            'array with null' => [
                'data' => ['armored_key' => null],
                'message' => 'An OpenPGP key must be provided.'
            ],
            'int' => [
                'data' => ['armored_key' => 100],
                'message' => 'A valid OpenPGP key must be provided.'
            ],
            'string' => [
                'data' => ['armored_key' => 'nope'],
                'message' => 'A valid OpenPGP key must be provided.'
            ],
            'partial key' => [
                'data' => ['armored_key' => $cutKey],
                'message' => 'A valid OpenPGP key must be provided.'
            ]
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
            'AuthenticationToken' => [
                'token' => $t->token
            ],
            'Gpgkey' => $case['data']
            ];
        }
        $this->postJson($url, $data);
        $this->assertError(400, $case['message'], 'Issue with case: ' . $caseName);
    }

    public function testSetupCompleteDeletedUserError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.sofia') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active or has been deleted.');
    }

    public function testSetupCompleteAlreadyActiveUserError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ada') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active or has been deleted.');
    }
}
