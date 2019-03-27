<?php
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
namespace App\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SetupCompleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/AuthenticationTokens'
    ];
    public $AuthenticationTokens;
    use AuthenticationTokenModelTrait;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
        parent::setUp();
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteApiV1Success()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER);
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
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

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteSuccess()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER);
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
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

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteErrorWithKeyBelongingToDeletedUser()
    {
        // Complete setup with sofia's key (deleted user)
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER);
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'sofia_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $t->token
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey
            ]
        ];
        $this->postJson($url, $data);
        $this->assertError();
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertContains('_isUnique', $this->_getBodyAsString());
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteInvalidUserIdError()
    {
        $url = '/setup/complete/nope.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user id is not valid.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteInvalidUserTokenError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.nope') . '.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user does not exist');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteInvalidAuthenticationTokenError()
    {
        $userId = UuidFactory::uuid('user.id.ruth');
        $url = '/setup/complete/' . $userId . '.json';
        $tokenExpired = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'expired');
        $tokenInactive = $this->quickDummyAuthToken($userId, AuthenticationToken::TYPE_REGISTER, 'inactive');
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
                'data' => ['token' => $tokenExpired],
                'message' => 'The authentication token is not valid or has expired.'
            ],
            'inactive token' => [
                'data' => ['token' => $tokenInactive],
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

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteInvalidGpgkeyError()
    {
        $t = $this->AuthenticationTokens->generate(UuidFactory::uuid('user.id.ruth'), AuthenticationToken::TYPE_REGISTER);
        $url = '/users/validateAccount/' . UuidFactory::uuid('user.id.ruth') . '.json';

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
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

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteDeletedUserError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.sofia') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active or has been deleted.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteAlreadyActiveUserError()
    {
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ada') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active or has been deleted.');
    }
}
