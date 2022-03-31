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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryRequests;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsViewControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function testAccountRecoveryRequestsViewController_ErrorNotFound()
    {
        $this->logInAsAdmin();
        $this->getJson('/account-recovery/requests/' . UuidFactory::uuid() . '.json');
        $this->assertError(404);
    }

    public function testAccountRecoveryRequestsViewController_Success()
    {
        $this->logInAsAdmin();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY)
            ->persist();

        AccountRecoveryRequestFactory::make()
            ->setField('id', UuidFactory::uuid('ar.request.id'))
            ->setField('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED)
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        $this->getJson('/account-recovery/requests/' . UuidFactory::uuid('ar.request.id') . '.json');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Check some fields are not returned
        $r1 = $this->_responseJsonBody;
        $this->assertTrue(!isset($r1->authentication_token_id));

        // Check some fields are not returned
        $this->assertTrue(!isset($r1->armored_key));
        $this->assertTrue(!isset($r1->account_recovery_private_key));
        $this->assertTrue(!isset($r1->account_recovery_responses));
    }

    public function testAccountRecoveryRequestsViewController_SuccessContain()
    {
        $this->logInAsAdmin();
        $user = UserFactory::make()->withAvatar()->user()->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', $user->id)
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY)
            ->persist();

        AccountRecoveryRequestFactory::make()
            ->setField('id', UuidFactory::uuid('ar.request.id'))
            ->setField('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED)
            ->setField('created_by', $user->id)
            ->setField('user_id', $user->id)
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        $options = '?contain[armored_key]=1';
        $options .= '&contain[creator]=1';
        $options .= '&contain[account_recovery_private_key_passwords]=1';
        $options .= '&contain[account_recovery_request_responses]=1';
        $this->getJson('/account-recovery/requests/' . UuidFactory::uuid('ar.request.id') . '.json' . $options);
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Check some fields are not returned
        $r1 = $this->_responseJsonBody;
        $this->assertTrue(!isset($r1->authentication_token_id));

        // Check some fields are now returned
        $this->assertTrue(isset($r1->armored_key));

        // Check private key
        $this->assertTrue(isset($r1->account_recovery_private_key));
        $k = $r1->account_recovery_private_key;
        $this->assertTrue(Validation::uuid($k->id));
        $this->assertTrue(Validation::uuid($k->user_id));
        $this->assertTrue(Validation::uuid($k->created_by));
        $this->assertTrue(Validation::uuid($k->modified_by));
        $this->assertTrue(!isset($k->data));
        $this->assertTrue(isset($k->created));
        $this->assertTrue(isset($k->modified));

        // Check password
        $this->assertTrue(isset($r1->account_recovery_private_key->account_recovery_private_key_passwords));
        $this->assertTrue(count($r1->account_recovery_private_key->account_recovery_private_key_passwords) === 1);
        $p = $r1->account_recovery_private_key->account_recovery_private_key_passwords[0];
        $this->assertTrue(Validation::uuid($p->id));
        $this->assertTrue(Validation::uuid($p->created_by));
        $this->assertTrue(Validation::uuid($p->modified_by));
        $this->assertTrue(Validation::uuid($p->private_key_id));
        $this->assertTrue(is_string($p->recipient_fingerprint));
        $this->assertTrue(is_string($p->recipient_foreign_model));
        $this->assertTrue(is_string($p->data));
        $this->assertTrue(is_string($p->data));
        $this->assertTrue(isset($p->created));
        $this->assertTrue(isset($p->modified));

        // Check Responses
        $this->assertTrue(isset($r1->account_recovery_responses));
        $this->assertTrue(count($r1->account_recovery_responses) === 1);
        $r = $r1->account_recovery_responses[0];
        $this->assertTrue(Validation::uuid($r->id));
        $this->assertTrue(is_string($p->recipient_foreign_model));
        $this->assertTrue(Validation::uuid($r->created_by));
        $this->assertTrue(Validation::uuid($r->modified_by));
        $this->assertTrue(!isset($r->data));
        $this->assertTrue(isset($r->created));
        $this->assertTrue(isset($r->modified));
    }

    public function testAccountRecoveryRequestsViewController_SuccessContainCreator()
    {
        $this->logInAsAdmin();

        $request = AccountRecoveryRequestFactory::make()
            ->with('Creator', UserFactory::make()->withAvatar())
            ->persist();

        $options = '?contain[creator]=1';
        $this->getJson('/account-recovery/requests/' . $request->id . '.json' . $options);
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(isset($this->_responseJsonBody->creator));
        $this->assertTrue(isset($this->_responseJsonBody->creator->profile));
        $this->assertTrue(isset($this->_responseJsonBody->creator->profile->avatar));
        $this->assertTrue(Validation::uuid($this->_responseJsonBody->creator->id));

        // Check reciprocal
        $options = '?contain[creator]=0';
        $this->getJson('/account-recovery/requests/' . $request->id . '.json' . $options);
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(!isset($this->_responseJsonBody->creator));
    }

    public function testAccountRecoveryRequestsViewController_ErrorForbidden()
    {
        $this->logInAsUser();
        $this->getJson('/account-recovery/requests.json');
        $this->assertError(403);
    }

    public function testAccountRecoveryRequestsViewController_ErrorBadRequest()
    {
        $this->logInAsAdmin();
        $this->getJson('/account-recovery/requests/nope.json');
        $this->assertError(400);
    }
}
