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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\EndToEnd;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Routing\Router;
use Cake\TestSuite\Fixture\FixtureStrategyInterface;
use Cake\TestSuite\Fixture\TruncateStrategy;
use CakephpTestSuiteLight\Sniffer\SnifferRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

/**
 * @group EndToEndTest
 */
class AccountRecoveryOptInHappyPathEndToEndTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;
    use GpgAdaSetupTrait;

    /**
     * @var \App\Model\Entity\User
     */
    protected static $user;
    /**
     * @var \App\Model\Entity\User
     */
    protected static $admin;
    /**
     * @var \App\Model\Entity\User[]
     */
    protected static $admins;
    /**
     * @var int The number of admins, including the one used as actor
     */
    protected static $nAdmins = 5;

    public function setUp(): void
    {
        parent::setUp();
        $this->deleteEmailQueue(); // Start each test with a clean email queue.
    }

    public function tearDown(): void
    {
        $this->renderAllEmails();
        parent::tearDown();
    }

    /**
     * Skip table truncation
     *
     * @return FixtureStrategyInterface
     */
    protected function getFixtureStrategy(): FixtureStrategyInterface
    {
        return new TruncateStrategy();
    }

    /**
     * Step 0:
     * Cleanup the database. The DB will not be cleaned-up before any tests in this class
     * Create an admin, a user and various other
     *
     * @group
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_Setup()
    {
        // Clean up all tables. This is done once only.
        SnifferRegistry::get('test')->truncateDirtyTables();
        self::$admin = UserFactory::make()->admin()->withAvatar()->persist();
        self::$admins = UserFactory::make(self::$nAdmins - 1)->admin()->withAvatar()->persist();
        self::$user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->adaPublicKey())
            ->withAvatar()->inactive()->persist();
        UserFactory::make(10)->withAvatar()->active()->persist();
        $this->assertSame(self::$nAdmins + 11, UserFactory::count());
    }

    /**
     * Step 1:
     * The admin sets the policy to opt-in
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_AdminSetPolicy()
    {
        $keyData = AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()->getEntity();
        $policyValue = 'opt-in';
        $data = [
            'policy' => $policyValue,
            'account_recovery_organization_public_key' => [
                'fingerprint' => $keyData->fingerprint,
                'armored_key' => $keyData->armored_key,
            ],
        ];

        $this->logInAs(self::$admin);
        $this->postJson('/account-recovery/organization-policies.json', $data);
        $this->assertResponseOk();

        // All admins receive an email
        $this->assertEmailQueueCount(self::$nAdmins);
    }

    /**
     * Step 2:
     * The user performs a setup with opt-in policy
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserSetup()
    {
        $user = self::$user;
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->persist();

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $organizationPublicKey */
        $organizationPublicKey = AccountRecoveryOrganizationPublicKeyFactory::find()->firstOrFail();
        $orkArmored = $organizationPublicKey->armored_key;
        $orkFingerprint = $organizationPublicKey->fingerprint;

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
            'locale' => 'fr_FR',
            'account_recovery_user_setting' => [
                'user_id' => $user->id,
                'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
                'account_recovery_private_key' => [
                    'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password_sig_ada.msg'),
                    'account_recovery_private_key_passwords' => [[
                        'recipient_fingerprint' => $orkFingerprint,
                        'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                        'data' => $this->encrypt($orkFingerprint, $orkArmored),
                    ]],
                ],
            ],
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertResponseOk();

        $this->assertTrue(UserFactory::get(self::$user->id)->active);
        // No emails sent at this stage
        $this->assertEmailQueueIsEmpty();
    }

    /**
     * Step 3:
     * User does start recovery process
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserStartsRecoveryProcess()
    {
        $this->markTestIncomplete();
    }

    /**
     * Step 4:
     * User validate email and setup temp key, create request
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserCreateRecoveryRequest()
    {
        $user = self::$user;
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();
        $data = GpgkeyFactory::make()->rsa4096Key_2()->getEntity();
        $payload = [
            'authentication_token' => [
                'token' => $token->token,
            ],
            'user_id' => $user->id,
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        $this->postJson('/account-recovery/requests.json', $payload);
        $this->assertResponseOk();

        $request = AccountRecoveryRequestFactory::find()->firstOrFail();
        $this->assertEmailQueueCount(self::$nAdmins + 1);
        $this->assertEmailInBatchContains(
            Router::url('/app/account-recovery/requests/review/' . $request->get('id'), true),
            self::$admin->username
        );
    }

    /**
     * Step 5:
     * Admin views request
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_AdminViewsRequest()
    {
        $request = AccountRecoveryRequestFactory::find()->firstOrFail();
        $this->logInAs(self::$admin);
        $this->getJson('/account-recovery/requests/' . $request->get('id') . '.json');
        $this->assertResponseOk();
    }

    /**
     * Step 6:
     * Admin approves the request
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_AdminApprovesRequest()
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->contain('AuthenticationTokens')->firstOrFail();
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = AccountRecoveryOrganizationPolicyFactory::find()->firstOrFail();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        $this->logInAs(self::$admin);
        $this->postJson('/account-recovery/responses.json', $data);
        $this->assertResponseOk();

        $this->assertEmailQueueCount(self::$nAdmins + 1);
        $this->assertEmailInBatchContains(
            Router::url('/account-recovery/continue/' . self::$user->id . '/' . $request->authentication_token->token, true),
            self::$user->username
        );
    }

    /**
     * Step 7:
     * User continues recovery
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserContinuesRecovery()
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->contain('AuthenticationTokens')->firstOrFail();
        $token = $request->authentication_token->token;

        $this->getJson('/account-recovery/continue/' . self::$user->id . '/' . $token . '.json');
        $this->assertResponseOk();
    }

    /**
     * Step 8:
     * User gets request status
     *
     * @return void
     * @see
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserGetRequestStatus()
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->contain('AuthenticationTokens')->firstOrFail();
        $token = $request->authentication_token->token;
        $this->getJson('/account-recovery/requests/' . $request->id . '/' . self::$user->id . '/' . $token . '.json');
        $this->assertResponseOk();
        $this->assertSame(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED, $this->_responseJsonBody->status);
    }

    /**
     * Step 9:
     * User finalizes the accepted recovery request
     *
     * @return void
     */
    public function testAccountRecoveryOptInHappyPathEndToEndTest_UserCompletesRecovery()
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->firstOrFail();
        $gpgkey = GpgkeyFactory::find()
            ->where(['user_id' => self::$user->id])
            ->firstOrFail();
        $token = AuthenticationTokenFactory::find()
            ->where([
                'user_id' => self::$user->id,
                'type' => AuthenticationToken::TYPE_RECOVER,
            ])
            ->firstOrFail();

        $data = [
            'authenticationtoken' => [
                'token' => $token->get('token'),
            ],
            'gpgkey' => [
                'armored_key' => $gpgkey->get('armored_key'),
            ],
            'account_recovery_request_id' => $request->id,
        ];
        $this->postJson('/setup/recover/complete/' . self::$user->id . '.json', $data);
        $this->assertResponseOk();
    }

    public function testAccountRecoveryOptInHappyPathEndToEndTest_AssertRequestAndResponses()
    {
        $this->assertSame(1, AccountRecoveryRequestFactory::count());
        $this->assertSame(1, AccountRecoveryResponseFactory::count());

        /** @var AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->firstOrFail();
        $this->assertTrue($request->isCompleted());
        /** @var AccountRecoveryResponse $response */
        $response = AccountRecoveryResponseFactory::find()->firstOrFail();
        $this->assertTrue($response->isApproved());

        // Check that all tokens have been consumed
        $activeTokens = AuthenticationTokenFactory::find()->where(['active' => true])->count();
        $this->assertSame(0, $activeTokens);
    }
}
