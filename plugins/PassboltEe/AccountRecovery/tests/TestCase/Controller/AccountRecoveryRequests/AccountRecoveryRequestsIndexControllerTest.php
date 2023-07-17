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

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsIndexControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function testAccountRecoveryRequestsIndexController_SuccessEmpty()
    {
        $this->logInAsAdmin();
        $this->getJson('/account-recovery/requests.json');

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(is_array($this->_responseJsonBody));
        $this->assertTrue(empty($this->_responseJsonBody));
    }

    public function testAccountRecoveryRequestsIndexController_SuccessPaginate()
    {
        $this->logInAsAdmin();

        AccountRecoveryRequestFactory::make(5)->persist();

        $options = 'limit=3&sort=AccountRecoveryRequests.modified&direction=desc';
        $this->getJson('/account-recovery/requests.json?' . $options);

        // Check response is ok
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(is_array($this->_responseJsonBody));

        // Check limit
        $this->assertTrue(count($this->_responseJsonBody) === 3);

        // Check which fields are returned
        $r1 = $this->_responseJsonBody[0];
        $this->assertTrue(Validation::uuid($r1->id));
        $this->assertTrue(Validation::uuid($r1->user_id));
        $this->assertNotEmpty($r1->fingerprint);
        $this->assertNotEmpty($r1->status);
        $this->assertNotEmpty($r1->modified);
        $this->assertNotEmpty($r1->created);
        $this->assertTrue(Validation::uuid($r1->created_by));
        $this->assertTrue(Validation::uuid($r1->modified_by));

        // Check some fields are not returned
        $this->assertTrue(!isset($r1->armored_key));
        $this->assertTrue(!isset($r1->authentication_token_id));
        $this->assertTrue(!isset($r1->account_recovery_private_key_passwords));
        $this->assertTrue(!isset($r1->account_recovery_request_responses));

        // Check sort order
        $d1 = new Chronos($r1->modified);
        $d2 = new Chronos($this->_responseJsonBody[1]->modified);
        $this->assertTrue($d1->gt($d2));
    }

    public function testAccountRecoveryRequestsIndexController_SuccessContain()
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
            ->setField('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED)
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        $options = '?contain[armored_key]=1';
        $options .= '&contain[account_recovery_private_key_passwords]=1';
        $options .= '&contain[account_recovery_request_responses]=1';
        $this->getJson('/account-recovery/requests.json' . $options);
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(is_array($this->_responseJsonBody));

        // Check some fields are not returned
        $r1 = $this->_responseJsonBody[0];
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

    public function testAccountRecoveryRequestsIndexController_Success_FilterHasUsers()
    {
        $ada = UuidFactory::uuid('user.id.ada');
        $betty = UuidFactory::uuid('user.id.betty');
        AccountRecoveryRequestFactory::make(5)->persist();
        AccountRecoveryRequestFactory::make()->withUser($ada)->persist();
        AccountRecoveryRequestFactory::make()->withUser($betty)->persist();

        $this->logInAsAdmin();
        $this->getJson("/account-recovery/requests.json?filter[has-users][]=$ada&filter[has-users][]=$betty");
        $this->assertTrue(count($this->_responseJsonBody) === 2);

        $this->getJson('/account-recovery/requests.json?filter[has-users][]=' . UuidFactory::uuid());
        $this->assertTrue(count($this->_responseJsonBody) === 0);
    }

    public function testAccountRecoveryRequestsIndexController_ErrorForbidden()
    {
        $this->logInAsUser();
        $this->getJson('/account-recovery/requests.json');
        $this->assertError(403);
    }
}
