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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryContinue;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryContinueControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function testAccountRecoveryContinueController_Page_ErrorTokenId()
    {
        $this->get('/account-recovery/continue/' . UuidFactory::uuid() . '/nope');
        $this->assertResponseCode(400);
    }

    public function testAccountRecoveryContinueController_Page_ErrorUserId()
    {
        $this->get('/account-recovery/continue/nope/' . UuidFactory::uuid());
        $this->assertResponseCode(400);
    }

    public function testAccountRecoveryContinueController_Page_Success()
    {
        $this->get('/account-recovery/continue/' . UuidFactory::uuid() . '/' . UuidFactory::uuid());
        $this->assertResponseCode(200);
    }

    public function testAccountRecoveryContinueController_Page_Success2()
    {
        $user = UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->active()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        $this->get('/account-recovery/continue/' . $user->id . '/' . $token->id);
        $this->assertTextContains('api-account-recovery.js', $this->_getBodyAsString());
        $this->assertResponseCode(200);
    }

    // JSON

    public function testAccountRecoveryContinueController_Error()
    {
        $this->getJson('/account-recovery/continue/' . UuidFactory::uuid() . '/' . UuidFactory::uuid() . '.json');
        $this->assertResponseCode(400);
    }

    public function testAccountRecoveryContinueController_Success()
    {
        $user = UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->active()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

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

        $this->getJson('/account-recovery/continue/' . $user->id . '/' . $token->token . '.json');
        $this->assertResponseCode(200);
    }
}
