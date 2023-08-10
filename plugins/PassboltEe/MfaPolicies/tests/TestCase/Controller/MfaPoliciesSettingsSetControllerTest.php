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
 * @since         3.10.0
 */

namespace Passbolt\MfaPolicies\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Routing\Exception\MissingRouteException;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Service\MfaPoliciesSetSettingsService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;

/**
 * @covers \Passbolt\MfaPolicies\Controller\MfaPoliciesSettingsSetController
 */
class MfaPoliciesSettingsSetControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin('MfaPolicies');

        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP so extended user access control don't fail
        $this->mockUserAgent();
        $this->mockUserIp();
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->disableFeaturePlugin('MfaPolicies');
    }

    public function testMfaPoliciesSettingsSet_Error_FeatureDisabled()
    {
        $this->disableFeaturePlugin('MfaPolicies');

        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]);
    }

    public function testMfaPoliciesSettingsSet_Error_Unauthenticated()
    {
        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]);

        $this->assertResponseCode(401);
    }

    public function testMfaPoliciesSettingsSet_Error_ForbiddenForUser()
    {
        $this->logInAsUser();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]);

        $this->assertForbiddenError('Only administrators are allowed');
    }

    public function testMfaPoliciesSettingsSet_Error_ValidationRequired()
    {
        $this->logInAsAdmin();

        $this->postJson('/mfa-policies/settings.json', []);

        $response = $this->_responseJsonBody;
        $this->assertBadRequestError('Could not validate the MFA policies settings');
        $this->assertObjectHasAttribute('policy', $response);
        $this->assertObjectHasAttribute('remember_me_for_a_month', $response);
        $this->assertStringContainsString('field is required', $response->policy->_required);
        $this->assertStringContainsString(
            'field is required',
            $response->remember_me_for_a_month->_required
        );
    }

    public function testMfaPoliciesSettingsSet_Error_ValidationInvalidValues()
    {
        $this->logInAsAdmin();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => 'super---policy',
            'remember_me_for_a_month' => 'string val',
        ]);

        $response = $this->_responseJsonBody;
        $this->assertBadRequestError('Could not validate the MFA policies settings');
        $this->assertObjectHasAttribute('policy', $response);
        $this->assertObjectHasAttribute('remember_me_for_a_month', $response);
        $this->assertStringContainsString('should be one of the following: opt-in, mandatory', $response->policy->inList);
        $this->assertStringContainsString(
            'should be a boolean type',
            $response->remember_me_for_a_month->boolean
        );
    }

    public function testMfaPoliciesSettingsSet_SuccessCreate()
    {
        $admin = $this->logInAsAdmin();
        /** Create dummy authentication tokens */
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_LOGIN)->active()->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => false])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]);

        $response = $this->_responseJsonBody;
        /** Make sure response is in correct format & values are valid. */
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $response->policy);
        $this->assertTrue($response->remember_me_for_a_month);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->created_by);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] $settings
         */
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ], $settings[0]->value);
        /** Assert MFA tokens doesn't get inactivated when option is enabled */
        $inactiveMfaTokens = MfaAuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_MFA, 'active' => false])
            ->count();
        $this->assertSame(0, $inactiveMfaTokens);
    }

    public function testMfaPoliciesSettingsSet_SuccessUpdate()
    {
        $admin = $this->logInAsAdmin();
        MfaPoliciesSettingFactory::make()->persist();
        /** Create dummy authentication tokens */
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_LOGIN)->active()->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => false])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ]);

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_MANDATORY, $response->policy);
        $this->assertFalse($response->remember_me_for_a_month);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] $settings
         */
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ], $settings[0]->value);
        /** Assert MFA tokens with `remember=true` are changed to `remember=false` */
        $mfaTokens = MfaAuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_MFA, 'active' => true])
            ->toArray();
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        foreach ($mfaTokens as $mfaToken) {
            $this->assertSame(false, $mfaToken->getJsonDecodedData()['remember']);
        }
    }

    public function testMfaPoliciesSettingsSet_Success_EmailSentOnCreate()
    {
        $admin = $this->logInAsAdmin();
        MfaPoliciesSettingFactory::make()->persist();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ]);

        $this->assertSuccess();
        $this->assertEventFired(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED);
        $this->assertEmailQueueCount(1);
        $this->assetEmailSubject($admin->username, 'You edited the MFA policy');
        $this->assertEmailIsInQueue([
            'email' => $admin->username,
            'template' => 'Passbolt/MfaPolicies.AD/settings_updated',
        ]);
        $this->assertEmailInBatchContains('MFA policy has been updated, the new configuration is as follow:', $admin->username);
        $this->assertEmailInBatchContains('Policy: Mandatory', $admin->username);
        $this->assertEmailInBatchContains('Remember me for a month: Disabled', $admin->username);
        $this->assertEmailInBatchContains('User Agent: ', $admin->username);
        $this->assertEmailInBatchContains('User IP: ', $admin->username);
    }

    public function testMfaPoliciesSettingsSet_Success_EmailSentOnUpdateToAllAdmins()
    {
        UserFactory::make(['username' => 'barry@test.test'])->user()->persist();
        $john = UserFactory::make(['username' => 'john@test.test'])->admin()->persist();
        $jane = UserFactory::make(['username' => 'jane@test.test'])->admin()->persist();
        $admin = $this->logInAsAdmin();
        MfaPoliciesSettingFactory::make()->persist();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]);

        $this->assertSuccess();
        $this->assertEventFired(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED);
        $this->assertEmailQueueCount(3);
        $this->assetEmailSubject($admin->username, 'You edited the MFA policy');
        $this->assetEmailSubject(
            $john->username,
            "{$admin->profile->first_name} edited the MFA policy"
        );
        $this->assertEmailIsInQueue([
            'email' => $admin->username,
            'template' => 'Passbolt/MfaPolicies.AD/settings_updated',
        ]);
        $this->assertEmailIsInQueue([
            'email' => $john->username,
            'template' => 'Passbolt/MfaPolicies.AD/settings_updated',
        ]);
        $this->assertEmailIsInQueue([
            'email' => $jane->username,
            'template' => 'Passbolt/MfaPolicies.AD/settings_updated',
        ]);
        /** Check email body sent to admin who performed the action */
        $this->assertEmailInBatchContains('MFA policy has been updated, the new configuration is as follow:', $admin->username);
        $this->assertEmailInBatchContains('Policy: Opt-in', $admin->username);
        $this->assertEmailInBatchContains('Remember me for a month: Enabled', $admin->username);
        $this->assertEmailInBatchContains('User Agent: ', $admin->username);
        $this->assertEmailInBatchContains('User IP: ', $admin->username);
        /** Check email body sent to John */
        $this->assertEmailInBatchContains('MFA policy has been updated, the new configuration is as follow:', $john->username);
        $this->assertEmailInBatchContains('Policy: Opt-in', $john->username);
        $this->assertEmailInBatchContains('Remember me for a month: Enabled', $john->username);
        $this->assertEmailInBatchContains('User Agent: ', $john->username);
        $this->assertEmailInBatchContains('User IP: ', $john->username);
        /** Check email body sent to Jane */
        $this->assertEmailInBatchContains('MFA policy has been updated, the new configuration is as follow:', $jane->username);
        $this->assertEmailInBatchContains('Policy: Opt-in', $jane->username);
        $this->assertEmailInBatchContains('Remember me for a month: Enabled', $jane->username);
        $this->assertEmailInBatchContains('User Agent: ', $jane->username);
        $this->assertEmailInBatchContains('User IP: ', $jane->username);
    }

    public function testMfaPoliciesSettingsSet_SuccessCreateWithNonDefaultValuesSetsMfaTokensInactive()
    {
        $this->logInAsAdmin();
        /** Create dummy authentication tokens */
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_LOGIN)->active()->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => false])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();

        $this->postJson('/mfa-policies/settings.json', [
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ]);

        $this->assertSuccess();
        /** Assert MFA tokens with `remember=true` are changed to `remember=false` */
        $mfaTokens = MfaAuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_MFA, 'active' => true])
            ->toArray();
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        foreach ($mfaTokens as $mfaToken) {
            $this->assertSame(false, $mfaToken->getJsonDecodedData()['remember']);
        }
    }
}
