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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryResponses;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Entity\Role;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryResponsesCreateServiceTest extends AccountRecoveryTestCase
{
    use UacAwareMiddlewareTrait;

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend $gpg
     */
    protected $gpg;

    public function testAccountRecoveryResponsesCreateService_Success_Approved()
    {
        [$request, $policy] = $this->startScenario();
        $admin = UserFactory::make()->admin()->active()->persist();
        $uac = $this->makeUac($admin);
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            $results = (new AccountRecoveryResponsesCreateService())->create($uac, $data);
        } catch (ValidationException | CustomValidationException $exception) {
            $this->fail($exception->getMessage());
        }

        // Check results
        $this->assertEquals(1, AccountRecoveryResponseFactory::count());
        $this->assertTrue(isset($results->id));
        $this->assertTrue(is_string($results->data));
        $this->assertTrue($results->responder_foreign_model === 'AccountRecoveryOrganizationKey');
        $this->assertTrue($results->responder_foreign_key === $policy->public_key_id);
        $this->assertTrue($results->modified_by === $uac->getId());
        $this->assertTrue($results->created_by === $uac->getId());

        // Check sort order
        $d1 = new Chronos($results->created);
        $d2 = new Chronos($results->modified);
        $this->assertTrue($d2->gt($d1));

        // Check request is updated
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());

        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $r = $table->find()->all()->toArray();
        $this->assertEquals($r[0]['status'], AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED);
        $this->assertEquals($r[0]['modified_by'], $uac->getId());
    }

    public function testAccountRecoveryResponsesCreateService_SuccessRejected()
    {
        [$request, $policy] = $this->startScenario();
        $admin = UserFactory::make()->admin()->active()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_REJECTED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
        ];

        try {
            $results = (new AccountRecoveryResponsesCreateService())->create($uac, $data);
        } catch (ValidationException | CustomValidationException $exception) {
            $this->fail();
        }

        // Check results
        $this->assertEquals(1, AccountRecoveryResponseFactory::count());
        $this->assertTrue(isset($results->id));
        $this->assertTrue(empty($results->data));
        $this->assertTrue($results->responder_foreign_model === 'AccountRecoveryOrganizationKey');
        $this->assertTrue($results->responder_foreign_key === $policy->public_key_id);
        $this->assertTrue($results->modified_by === $uac->getId());
        $this->assertTrue($results->created_by === $uac->getId());

        // Check sort order
        $d1 = new Chronos($results->created);
        $d2 = new Chronos($results->modified);
        $this->assertTrue($d2->gt($d1));

        // Check request is updated
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());

        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $r = $table->find()->all()->toArray();
        $this->assertEquals($r[0]['status'], AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED);
        $this->assertEquals($r[0]['modified_by'], $uac->getId());
    }

    // Error scenarios

    public function testAccountRecoveryResponsesCreateService_Error_DisabledSetting()
    {
        // Not set
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $data = [];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('disabled', $exception->getMessage());
        }

        // Set to disabled
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('disabled', $exception->getMessage());
        }
    }

    // Errors of account_recovery_request_id

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_NotSet()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            //'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['_required']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_NotUuid()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => 'nope',
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['uuid']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_NotFound()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => UuidFactory::uuid(),
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['exists']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_Completed()
    {
        [$request, $policy] = $this->startScenario(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_COMPLETED);
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['isRequestPendingRule']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_Rejected()
    {
        [$request, $policy] = $this->startScenario(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED);
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['isRequestPendingRule']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_Approved()
    {
        [$request, $policy] = $this->startScenario(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED);
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['account_recovery_request_id']['isRequestPendingRule']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_RequestId_PendingResponseAlreadyProvided()
    {
        $this->markTestSkipped('Only happens in data integrity issue scenario');
    }

    // Errors of status

    public function testAccountRecoveryResponsesCreateService_Error_Status_NotSet()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            //'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['status']['_required']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Status_NotString()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => ['something' => 'else'],
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['status']['scalar']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Status_NotInList()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => 'nope',
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['status']['inList']));
        }
    }

    // Errors of foreign model name

    public function testAccountRecoveryResponsesCreateService_Error_ForeignModel_NotSet()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            //'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['responder_foreign_model']['_required']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_ForeignModel_NotString()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => ['AccountRecoveryOrganizationKey'],
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['responder_foreign_model']['scalar']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_ForeignModel_NotInList()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'nope',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['responder_foreign_model']['inList']));
        }
    }

    // Errors of data field

    public function testAccountRecoveryResponsesCreateService_Error_Data_RejectedSet()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_REJECTED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['notRequiredRule']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Data_ApprovedNotSet()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        //$password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            //'data' => $password,
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['required']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Data_NotString()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => [$password],
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['isValidOpenPGPMessage']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Data_NotParsable()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => 'not parsable',
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['isValidOpenPGPMessage']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Data_NotAsymetric()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password_sig_ada.msg'),
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['hasAsymmetricPacketRule']));
        }
    }

    public function testAccountRecoveryResponsesCreateService_Error_Data_WrongRecipient()
    {
        [$request, $policy] = $this->startScenario();
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => 'AccountRecoveryOrganizationKey',
            'responder_foreign_key' => $policy->public_key_id,
            'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg'),
        ];

        try {
            (new AccountRecoveryResponsesCreateService())->create($uac, $data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['data']['wrongRecipient']));
        }
    }

    // Scenario help

    /**
     * @param string|null $status
     * @return array
     * @throws \Exception
     */
    protected function startScenario(?string $status = AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING): array
    {
        UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        $policy = AccountRecoveryOrganizationPolicyFactory::make()
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
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('recipient_foreign_model', 'AccountRecoveryOrganizationKey')
            ->persist();

        $request = AccountRecoveryRequestFactory::make()
            ->setField('status', $status)
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->setField('fingerprint', '23C6C30E241324C90A44A719A86A7EA3739797F5')
            ->setField('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'))
            ->persist();

        return [$request, $policy];
    }

    /**
     * Utility function to speed up encryption step in test cases
     *
     * @param string $fingerprint fingerprint
     * @param string $key armored key block
     * @return string
     */
    private function encrypt(string $fingerprint, string $key): string
    {
        // Build the data
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->clearKeys();
        $this->gpg->importKeyIntoKeyring($key);
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);

        return $this->gpg->encrypt('Foo');
    }
}