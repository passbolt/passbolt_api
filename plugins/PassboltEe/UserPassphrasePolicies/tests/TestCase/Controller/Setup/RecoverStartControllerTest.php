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
 * @since         4.3.0
 */
namespace Passbolt\UserPassphrasePolicies\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\AccountRecovery\AccountRecoveryPlugin;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;
use Passbolt\UserPassphrasePolicies\Test\Factory\UserPassphrasePoliciesSettingFactory;
use Passbolt\UserPassphrasePolicies\UserPassphrasePoliciesPlugin;

/**
 * @covers \App\Controller\Setup\RecoverStartController
 */
class RecoverStartControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(AccountRecoveryPlugin::class);
        $this->enableFeaturePlugin(UserPassphrasePoliciesPlugin::class);
    }

    public function testRecoverStartWithUserPassphrasePolicies_Success_DefaultValues()
    {
        $this->disableFeaturePlugin(AccountRecoveryPlugin::class);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->active()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();

        $this->getJson("/setup/recover/start/{$user->id}/{$token->token}.json");

        $response = $this->_responseJsonBody;
        $this->assertResponseOk();
        $this->assertNotNull($response->user);
        $this->assertUserAttributes($response->user);
        $this->assertNotNull($response->user->locale);
        $this->assertNotNull($response->user->profile);
        $this->assertNotNull($response->user->role);
        $this->assertObjectHasAttribute('user_passphrase_policy', $response);
        // Assert policy field values
        $defaultUserPassphraseSettings = UserPassphrasePoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertSame($defaultUserPassphraseSettings['entropy_minimum'], $response->user_passphrase_policy->entropy_minimum);
        $this->assertSame($defaultUserPassphraseSettings['external_dictionary_check'], $response->user_passphrase_policy->external_dictionary_check);
        $this->assertSame($defaultUserPassphraseSettings['source'], $response->user_passphrase_policy->source);
        $this->assertObjectNotHasAttributes(
            ['id', 'created', 'created_by', 'modified', 'modified_by'],
            $response->user_passphrase_policy
        );
    }

    public function testRecoverStartWithUserPassphrasePolicies_Success_DatabaseValues()
    {
        $this->disableFeaturePlugin(AccountRecoveryPlugin::class);
        UserPassphrasePoliciesSettingFactory::make([
            'value.entropy_minimum' => 64,
            'value.external_dictionary_check' => false,
        ])->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->active()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();

        $this->getJson("/setup/recover/start/{$user->id}/{$token->token}.json");

        $this->assertResponseOk();
        $response = $this->_responseJsonBody;
        $this->assertNotNull($response->user);
        $this->assertUserAttributes($response->user);
        $this->assertNotNull($response->user->locale);
        $this->assertNotNull($response->user->profile);
        $this->assertNotNull($response->user->role);
        $this->assertObjectNotHasAttribute('account_recovery_organization_policy', $response);
        $this->assertObjectHasAttribute('user_passphrase_policy', $response);
        // Assert policy field values
        $this->assertSame(64, $response->user_passphrase_policy->entropy_minimum);
        $this->assertSame(false, $response->user_passphrase_policy->external_dictionary_check);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE, $response->user_passphrase_policy->source);
        $this->assertObjectHasAttributes(
            ['id', 'created', 'created_by', 'modified', 'modified_by'],
            $response->user_passphrase_policy
        );
    }

    public function testAccountRecoveryRecoverStartWithUserPassphrasePolicies_Success_DefaultValues()
    {
        $status = 'foo_status';
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $setting */
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser()
            ->setField('status', $status)
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = $setting->user;
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();

        $this->getJson("/setup/recover/start/{$user->id}/{$token->token}.json");

        $response = $this->_responseJsonBody;
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('user', $response);
        $this->assertSame($status, $response->user->account_recovery_user_setting->status);
        $this->assertObjectHasAttribute('user_passphrase_policy', $response);
        // Assert policy field values
        $defaultUserPassphraseSettings = UserPassphrasePoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertSame($defaultUserPassphraseSettings['entropy_minimum'], $response->user_passphrase_policy->entropy_minimum);
        $this->assertSame($defaultUserPassphraseSettings['external_dictionary_check'], $response->user_passphrase_policy->external_dictionary_check);
        $this->assertSame($defaultUserPassphraseSettings['source'], $response->user_passphrase_policy->source);
        $this->assertObjectNotHasAttributes(
            ['id', 'created', 'created_by', 'modified', 'modified_by'],
            $response->user_passphrase_policy
        );
    }

    public function testAccountRecoveryRecoverStartWithUserPassphrasePolicies_Success_DatabaseValues()
    {
        $status = 'foo_status';
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $setting */
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser()
            ->setField('status', $status)
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = $setting->user;
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();
        // Pre-fill user passphrase policies in DB
        UserPassphrasePoliciesSettingFactory::make([
            'value.entropy_minimum' => 80,
            'value.external_dictionary_check' => false,
        ])->persist();

        $this->getJson("/setup/recover/start/{$user->id}/{$token->token}.json");

        $response = $this->_responseJsonBody;
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('user', $response);
        $this->assertSame($status, $response->user->account_recovery_user_setting->status);
        $this->assertObjectHasAttribute('user_passphrase_policy', $response);
        // Assert policy field values
        $this->assertSame(80, $response->user_passphrase_policy->entropy_minimum);
        $this->assertSame(false, $response->user_passphrase_policy->external_dictionary_check);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE, $response->user_passphrase_policy->source);
        $this->assertObjectHasAttributes(
            ['id', 'created', 'created_by', 'modified', 'modified_by'],
            $response->user_passphrase_policy
        );
    }
}
