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
use App\Test\Factory\AvatarFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

class RecoverCompleteControllerTest extends AppIntegrationTestCase
{
    use AuthenticationTokenModelTrait;
    use EmailQueueTrait;

    public $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteSuccess()
    {
        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        Configure::write('passbolt.plugins.log.enabled', true);
        $admins = UserFactory::make(3)
            ->with('Profiles.Avatars', AvatarFactory::make()->setDataWithFileContent())
            ->admin()
            ->persist();

        UserFactory::make(5) // Add some inactive admins that should not receive an email
            ->with('Profiles.Avatars')
            ->admin()
            ->inactive()
            ->persist();

        // The user performing the recovery is an admin to make sure that he does not receive two emails.
        $user = UserFactory::make()
            ->with('Profiles.Avatars', AvatarFactory::make()->setDataWithFileContent())
            ->admin()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();

        $t = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
        $url = '/setup/recover/complete/' . $user->id . '.json';
        $armoredKey = $user->gpgkey->armored_key;
        $data = [
            'authenticationtoken' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];

        $userAgent = 'FooAgent';
        $clientIP = '1.2.3.4';

        $mockrequest = $this->createMock(ServerRequest::class);
        $mockrequest->method('getData')->willReturn($data);
        $mockrequest->method('clientIp')->willReturn($clientIP);
        $mockrequest->method('getEnv')->willReturn($userAgent);
        $this->mockService(ServerRequest::class, function () use ($mockrequest) {
            return $mockrequest;
        });

        $this->postJson($url, $data);
        $this->assertSuccess();

        // Check that token is now inactive
        $t2 = $this->AuthenticationTokens->get($t->id);
        $this->assertFalse($t2->active);

        $this->assertEmailQueueCount(count($admins) + 1);
        // Check that the user got notified
        $this->assertEmailInBatchContains(
            'You just completed an account recovery.',
            $user->username,
        );
        $this->assertEmailInBatchContains(
            "User Agent: <i>$userAgent</i><br/>User IP: <i>$clientIP</i>",
            $user->username,
        );
        // Check that all admins got notified, as well as the user
        foreach ($admins as $admin) {
            $this->assertEmailInBatchContains(
                "{$user->profile->first_name} ({$user->username}) just completed an account recovery.",
                $admin->username,
            );
            $this->assertEmailInBatchContains(
                "User Agent: <i>$userAgent</i><br/>User IP: <i>$clientIP</i>",
                $admin->username,
            );
        }
        Configure::write('passbolt.plugins.log.enabled', $logEnabled);
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteInvalidUserIdError()
    {
        $url = '/setup/recover/complete/nope.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400);
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteInvalidUserTokenError()
    {
        $url = '/setup/recover/complete/' . UuidFactory::uuid('user.id.nope') . '.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user does not exist');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteInvalidAuthenticationTokenError()
    {
        $user = UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();
        $userId = $user->id;
        $armoredKey = $user->gpgkey->armored_key;
        $url = '/setup/recover/complete/' . $userId . '.json';

        $tokenExpired = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->expired()
            ->userId($userId)
            ->active()
            ->persist();
        $tokenInactive = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->inactive()
            ->userId($userId)
            ->persist();

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
                'data' => ['token' => $tokenExpired->token],
                'message' => 'The authentication token is not valid.',
            ],
            'inactive token' => [
                'data' => ['token' => $tokenInactive->token],
                'message' => 'The authentication token is not valid.',
            ],
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
                'authenticationtoken' => $case['data'],
                'gpgkey' => [
                    'armored_key' => $armoredKey,
                ],
            ];
            $this->postJson($url, $data);
            $this->assertError(400, $case['message'], 'Issue with test case: ' . $caseName);
        }
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteAuthenticationTokenTypeError()
    {
        $user = UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();
        $userId = $user->id;
        $armoredKey = $user->gpgkey->armored_key;
        $url = '/setup/recover/complete/' . $userId . '.json';
        $tokenWrongType = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_LOGIN)
            ->userId($userId)
            ->active()
            ->persist();
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');

        $fails = [
            'wrong type token' => [
                'data' => ['token' => $tokenWrongType->token],
                'message' => 'The authentication token is not valid.',
            ],
        ];
        foreach ($fails as $caseName => $case) {
            $data = [
                'authenticationtoken' => $case['data'],
                'gpgkey' => [
                    'armored_key' => $armoredKey,
                ],
            ];
            $this->postJson($url, $data);
            $this->assertError(400, $case['message'], 'Issue with test case: ' . $caseName);
        }
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteInvalidGpgkeyError()
    {
        $user = UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();
        $userId = $user->id;
        $armoredKey = $user->gpgkey->armored_key;

        $t = $this->AuthenticationTokens->generate($userId, AuthenticationToken::TYPE_RECOVER);
        $url = '/setup/recover/complete/' . $userId . '.json';

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
            'authenticationtoken' => [
                'token' => $t->token,
            ],
            'gpgkey' => $case['data'],
            ];
        }
        $this->postJson($url, $data);
        $this->assertError(400, $case['message'], 'Issue with case: ' . $caseName);
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteDeletedUserError()
    {
        $url = '/setup/recover/complete/' . UuidFactory::uuid('user.id.sofia') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist');
    }

    /**
     * @group AN
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteInactiveUserError()
    {
        $url = '/setup/recover/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist');
    }
}
