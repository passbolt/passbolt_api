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

namespace Passbolt\UserPassphrasePolicies\Test\TestCase\Controller\AccountRecovery;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\AccountRecoveryPlugin;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;
use Passbolt\UserPassphrasePolicies\Test\Factory\UserPassphrasePoliciesSettingFactory;
use Passbolt\UserPassphrasePolicies\UserPassphrasePoliciesPlugin;

/**
 * @covers \Passbolt\AccountRecovery\Controller\AccountRecoveryContinue\AccountRecoveryContinueController
 */
class AccountRecoveryContinueControllerTest extends AppIntegrationTestCase
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

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->disableFeaturePlugin(AccountRecoveryPlugin::class);
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        parent::tearDown();
    }

    public function testAccountRecoveryContinueController_Success_WithUserPassphrasePoliciesEnabledSourceDefault()
    {
        [$user, $token] = $this->persistAccountRecoveryData();

        $this->getJson('/account-recovery/continue/' . $user->id . '/' . $token->token . '.json');

        $this->assertResponseCode(200);
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('user_passphrase_policy', $response);
        $this->assertEqualsCanonicalizing(
            UserPassphrasePoliciesSettingsDto::createFromDefault()->toFilteredArray(),
            $response['user_passphrase_policy']
        );
    }

    public function testAccountRecoveryContinueController_Success_WithUserPassphrasePoliciesEnabledSourceDatabase()
    {
        [$user, $token] = $this->persistAccountRecoveryData();
        UserPassphrasePoliciesSettingFactory::make([
            'value.entropy_minimum' => 80,
            'value.external_dictionary_check' => false,
        ])->persist();

        $this->getJson('/account-recovery/continue/' . $user->id . '/' . $token->token . '.json');

        $this->assertResponseCode(200);
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('user_passphrase_policy', $response);
        $this->assertArrayHasAttributes(['id', 'created', 'created_by', 'modified', 'modified_by'], $response['user_passphrase_policy']);
        $this->assertEqualsCanonicalizing(
            [
                'entropy_minimum' => 80,
                'external_dictionary_check' => false,
                'source' => UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE,
            ],
            [
                'entropy_minimum' => $response['user_passphrase_policy']['entropy_minimum'],
                'external_dictionary_check' => $response['user_passphrase_policy']['external_dictionary_check'],
                'source' => $response['user_passphrase_policy']['source'],
            ]
        );
    }

    public function testAccountRecoveryContinueController_Success_WithUserPassphrasePoliciesDisabled()
    {
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);
        [$user, $token] = $this->persistAccountRecoveryData();

        $this->getJson('/account-recovery/continue/' . $user->id . '/' . $token->token . '.json');

        $this->assertResponseCode(200);
        $this->assertNull($this->getResponseBodyAsArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Helper methods
    |---------------------------------------------------------------------------
    */

    private function persistAccountRecoveryData(): array
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->active()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'id' => UuidFactory::uuid('acr.org_public_key.id'),
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', $user->id)
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', $user->id)
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('recipient_foreign_model', 'AccountRecoveryOrganizationKey')
            ->persist();

        AccountRecoveryRequestFactory::make()
            ->setField('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED)
            ->setField('user_id', $user->id)
            ->setField('authentication_token_id', $token->id)
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        return [$user, $token];
    }
}
