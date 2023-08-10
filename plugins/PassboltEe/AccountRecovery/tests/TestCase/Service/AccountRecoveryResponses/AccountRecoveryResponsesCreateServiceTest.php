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
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Http\Exception\BadRequestException;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;
use Passbolt\AccountRecovery\Test\Scenario\Request\ResponseCreateScenario;

class AccountRecoveryResponsesCreateServiceTest extends AccountRecoveryTestCase
{
    use GpgAdaSetupTrait;
    use ScenarioAwareTrait;

    public function testAccountRecoveryResponsesCreateService_Success_Approved()
    {
        [$request, $policy, $user, $authenticationToken] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        $this->assertTrue($results->responder_foreign_model === AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY);
        $this->assertTrue($results->responder_foreign_key === $policy->public_key_id);
        $this->assertTrue($results->modified_by === $uac->getId());
        $this->assertTrue($results->created_by === $uac->getId());

        // Check sort order
        $d1 = new Chronos($results->created);
        $d2 = new Chronos($results->modified);
        $this->assertTrue($d2->gt($d1));

        // Check request is updated
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());

        $r = AccountRecoveryRequestFactory::find()->toArray();
        $this->assertEquals($r[0]['status'], AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED);
        $this->assertEquals($r[0]['modified_by'], $uac->getId());

        // Check that the token is active
        $this->assertTrue(AuthenticationTokenFactory::get($authenticationToken->id)->isActive());
    }

    public function testAccountRecoveryResponsesCreateService_SuccessRejected()
    {
        [$request, $policy, $user, $authenticationToken] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_REJECTED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        $this->assertTrue($results->responder_foreign_model === AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY);
        $this->assertTrue($results->responder_foreign_key === $policy->public_key_id);
        $this->assertTrue($results->modified_by === $uac->getId());
        $this->assertTrue($results->created_by === $uac->getId());

        // Check sort order
        $d1 = new Chronos($results->created);
        $d2 = new Chronos($results->modified);
        $this->assertTrue($d2->gt($d1));

        // Check request is updated
        $this->assertEquals(1, AccountRecoveryRequestFactory::count());

        $r = AccountRecoveryRequestFactory::find()->toArray();
        $this->assertEquals($r[0]['status'], AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED);
        $this->assertEquals($r[0]['modified_by'], $uac->getId());

        // Check that the token has been deactivated
        $this->assertTrue(AuthenticationTokenFactory::get($authenticationToken->id)->isNotActive());
    }

    // Error scenarios

    public function testAccountRecoveryResponsesCreateService_Error_DisabledSetting()
    {
        // Not set
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            //'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => 'nope',
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => UuidFactory::uuid(),
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(
            ResponseCreateScenario::class,
            AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_COMPLETED
        );
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(
            ResponseCreateScenario::class,
            AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED
        );
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(
            ResponseCreateScenario::class,
            AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED
        );
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            //'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => ['something' => 'else'],
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => 'nope',
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            //'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => [AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY],
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_REJECTED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        //$password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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
        [$request, $policy] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
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

    public function testAccountRecoveryResponsesCreateService_ExpiredToken()
    {
        [$request, $policy, $user, $authenticationToken] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        AuthenticationTokenFactory::make($authenticationToken)->expired()->persist();
        $this->assertTrue(AuthenticationTokenFactory::get($authenticationToken->id)->isExpired());

        $uac = UserFactory::make()->admin()->active()->persistedUAC();
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        try {
            $results = (new AccountRecoveryResponsesCreateService())->create($uac, $data);
        } catch (ValidationException | CustomValidationException $exception) {
            $this->fail($exception->getMessage());
        }

        // Check that the token is active
        $token = AuthenticationTokenFactory::get($authenticationToken->id);
        $this->assertTrue($token->isActive());
        $this->assertFalse($token->isExpired());
    }
}
