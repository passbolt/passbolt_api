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
namespace App\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;

class SetupCompleteControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_Success()
    {
        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        Configure::write('passbolt.plugins.log.enabled', true);
        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        // Check key is saved
        $key = TableRegistry::getTableLocator()->get('Gpgkeys')
            ->find()->select()->where(['user_id' => $user->id])->first();
        $this->assertEquals($armoredKey, $key->armored_key);
        $this->assertEquals('7A795A51A4ABEC4A79AA64BBD5A3CA6EFA858DEE', $key->fingerprint);

        // Check auth token is disabled
        $token = TableRegistry::getTableLocator()->get('AuthenticationTokens')
            ->find()->select()->where(['token' => $t->token])->first();
        $this->assertEquals(false, $token->active);

        // Check that the locale in the payload was stored in the user's settings.
        $userLocale = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings')
            ->getFirstPropertyOrFail($user->id, LocaleService::SETTING_PROPERTY)
            ->value;
        $this->assertSame('fr-FR', $userLocale);
        $this->assertEmailIsInQueue([
            'email' => $admin1->username,
            'template' => 'LU/user_setup_complete',
            'subject' => $user->profile->first_name . ' just activated their account on passbolt',
        ]);
        $this->assertEmailIsInQueue([
            'email' => $admin2->username,
            'template' => 'LU/user_setup_complete',
            'subject' => $user->profile->first_name . ' just activated their account on passbolt',
        ]);
        $this->assertEmailQueueCount(2);
        Configure::write('passbolt.plugins.log.enabled', $logEnabled);
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_Success_BackwardCompat()
    {
        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        Configure::write('passbolt.plugins.log.enabled', true);
        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        // Check key is saved
        $key = TableRegistry::getTableLocator()->get('Gpgkeys')
            ->find()->select()->where(['user_id' => $user->id])->first();
        $this->assertEquals($armoredKey, $key->armored_key);
        $this->assertEquals('7A795A51A4ABEC4A79AA64BBD5A3CA6EFA858DEE', $key->fingerprint);

        // Check auth token is disabled
        $token = TableRegistry::getTableLocator()->get('AuthenticationTokens')
            ->find()->select()->where(['token' => $t->token])->first();
        $this->assertEquals(false, $token->active);

        // Check that the locale in the payload was stored in the user's settings.
        $userLocale = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings')
            ->getFirstPropertyOrFail($user->id, LocaleService::SETTING_PROPERTY)
            ->value;
        $this->assertSame('fr-FR', $userLocale);
        $this->assertEmailIsInQueue([
            'email' => $admin1->username,
            'template' => 'LU/user_setup_complete',
            'subject' => $user->profile->first_name . ' just activated their account on passbolt',
        ]);
        $this->assertEmailIsInQueue([
            'email' => $admin2->username,
            'template' => 'LU/user_setup_complete',
            'subject' => $user->profile->first_name . ' just activated their account on passbolt',
        ]);
        $this->assertEmailQueueCount(2);
        Configure::write('passbolt.plugins.log.enabled', $logEnabled);
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_SuccessEcc()
    {
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_nistp521_public.key');
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        $userK = TableRegistry::getTableLocator()->get('Gpgkeys')
            ->find()
            ->where(['fingerprint' => 'AEE8E22ACFBF70527C1BD918F571FEB3B15105EE'])
            ->firstOrFail();

        $this->assertEquals('ECDSA', $userK->type);
        $this->assertEquals('521', $userK->bits);
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_ErrorWithExpiredKey()
    {
        // Complete setup with sofia's key (deleted user)
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();

        $url = '/setup/complete/' . $t->user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key'),
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError();
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertStringContainsString('expired', $this->_getBodyAsString());
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_ErrorWithWeakKey()
    {
        // Complete setup with sofia's key (deleted user)
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();

        $url = '/setup/complete/' . $t->user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'elgamal_public.key'),
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError();
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertStringContainsString('isValidAlgorithmStrictRule', $this->_getBodyAsString());
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_ErrorWithKeyBelongingToDeletedUser()
    {
        // Complete setup with sofia's key (deleted user)
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();

        $user = $t->user;
        $deletedUser = UserFactory::make()
            ->deleted()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();

        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = $deletedUser->gpgkey->armored_key;
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError();
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertStringContainsString('_isUnique', $this->_getBodyAsString());
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_InvalidUserIdError()
    {
        $url = '/setup/complete/nope.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user identifier should be a valid UUID.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_InvalidUserTokenError()
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
    public function testSetupComplete_InvalidAuthenticationTokenError()
    {
        $user = UserFactory::make()->inactive()->persist();
        $url = '/setup/complete/' . $user->id . '.json?api-version=v2';
        $tokenInactive = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->inactive()
            ->userId($user->id)
            ->persist()->token;

        $tokenExpired = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->active()
            ->expired()
            ->userId($user->id)
            ->persist()->token;

        $fails = [
            'empty array' => [
                'data' => [],
                'message' => 'An authentication token should be provided.',
            ],
            'null' => [
                'data' => null,
                'message' => 'An authentication token should be provided.',
            ],
            'array with null' => [
                'data' => ['token' => null],
                'message' => 'An authentication token should be provided.',
            ],
            'int' => [
                'data' => ['token' => 100],
                'message' => 'The authentication token should be a valid UUID.',
            ],
            'string' => [
                'data' => ['token' => 'nope'],
                'message' => 'The authentication token should be a valid UUID.',
            ],
            'expired token' => [
                'data' => ['token' => $tokenExpired],
                'message' => 'The authentication token is not valid.',
            ],
            'inactive token' => [
                'data' => ['token' => $tokenInactive],
                'message' => 'The authentication token is not valid.',
            ],
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
                'authentication_token' => $case['data'],
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
    public function testSetupComplete_InvalidGpgkeyError()
    {
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/users/validateAccount/' . $user->id . '.json?api-version=v2';

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $cutKey = substr($armoredKey, 0, strlen($armoredKey) / 2);
        $fails = [
            'empty array' => [
                'data' => [],
                'message' => 'An OpenPGP key must be provided.',
            ],
            'null' => [
                'data' => null,
                'message' => 'An OpenPGP key must be provided.',
            ],
            'array with null' => [
                'data' => ['armored_key' => null],
                'message' => 'An OpenPGP key must be provided.',
            ],
            'int' => [
                'data' => ['armored_key' => 100],
                'message' => 'A valid OpenPGP key must be provided.',
            ],
            'string' => [
                'data' => ['armored_key' => 'nope'],
                'message' => 'A valid OpenPGP key must be provided.',
            ],
            'partial key' => [
                'data' => ['armored_key' => $cutKey],
                'message' => 'A valid OpenPGP key must be provided.',
            ],
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
                'authentication_token' => [
                    'token' => $t->token,
                ],
                'gpgkey' => $case['data'],
            ];
            $this->postJson($url, $data);
            $this->assertError(400, $case['message'], 'Issue with case: ' . $caseName);
        }
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_DeletedUserError()
    {
        $user = UserFactory::make()->active()->deleted()->persist();
        $url = '/setup/complete/' . $user->id . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_AlreadyActiveUserError()
    {
        $user = UserFactory::make()->active()->persist();
        $url = '/setup/complete/' . $user->id . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist or is already active.');
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_Error_FuturamaKey()
    {
        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'fry_public.key');
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The OpenPGP armored key could not be validated.');
        $this->assertNotEmpty($this->_responseJsonBody->gpgkey->key_created->custom);
        $this->assertFalse(OpenPGPBackendFactory::get()->isKeyInKeyring('8F83E4120302FFAE8884D6E5BD2BC91258CA79B3'));
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupComplete_Error_BrokenKey()
    {
        OpenPGPBackendFactory::get()->deleteKey('26FD986838F4F9AB318FF56AE5DFCEE142949B78');
        $armoredKeyOk = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key');
        OpenPGPBackendFactory::get()->importKeyIntoKeyring($armoredKeyOk);
        $this->assertTrue(OpenPGPBackendFactory::get()->isKeyInKeyring('26FD986838F4F9AB318FF56AE5DFCEE142949B78'));

        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa3072_public_broken.key');
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The OpenPGP key can not be used to encrypt.');
        $this->assertFalse(OpenPGPBackendFactory::get()->isKeyInKeyring('AE77104424962CDF8C2BB6A53C557610555EC24C'));
    }
}
