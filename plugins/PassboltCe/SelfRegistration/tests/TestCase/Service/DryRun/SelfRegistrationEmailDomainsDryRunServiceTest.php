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

namespace Passbolt\SelfRegistration\Test\TestCase\Service\DryRun;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\FormValidationException;
use App\Test\Factory\UserFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationEmailDomainsDryRunService;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationEmailDomainsDryRunService
 */
class SelfRegistrationEmailDomainsDryRunServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationEmailDomainsDryRunService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationEmailDomainsDryRunService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationEmailDomainsDryRunService_isSelfRegistrationOpen_Success()
    {
        $this->setSelfRegistrationSettingsData();
        $this->assertTrue($this->service->isSelfRegistrationOpen());
    }

    public function testSelfRegistrationEmailDomainsDryRunService_isSelfRegistrationOpen_No_Data_In_DB_Should_Return_False()
    {
        $this->assertFalse($this->service->isSelfRegistrationOpen());
    }

    public function testSelfRegistrationEmailDomainsDryRunService_isSelfRegistrationOpen_Invalid_Settings_In_DB_Should_ThrowException()
    {
        $this->setSelfRegistrationSettingsData('provider', 'foo');
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Could not validate the self registration settings found in database.');
        $this->service->isSelfRegistrationOpen();
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Valid()
    {
        $email = 'johndoe@passbolt.com';
        $this->setSelfRegistrationSettingsData();
        $this->assertTrue(
            $this->service->canGuestSelfRegister(compact('email'))
        );
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Soft_Deleted_User_Already_In_DB_Should_Succeed()
    {
        $email = 'johndoe@passbolt.com';
        $this->setSelfRegistrationSettingsData();
        UserFactory::make()->setField('username', $email)->deleted()->persist();
        $this->assertTrue(
            $this->service->canGuestSelfRegister(compact('email'))
        );
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_No_Settings_Should_Throw_Forbidden_Exception()
    {
        $email = 'johndoe@not-passbolt.com';
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('The self registration is disabled.');
        $this->service->canGuestSelfRegister(compact('email'));
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Email_Not_Allowed_Should_Throw_Validation_Exception()
    {
        $email = 'johndoe@not-passbolt.com';
        $this->setSelfRegistrationSettingsData();
        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The self registration data could not be validated.');
        $this->service->canGuestSelfRegister(compact('email'));
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Email_Not_Set_Should_Throw_Validation_Exception()
    {
        $email = 'johndoe@not-an-email';
        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('The self registration data could not be validated.');
        $this->service->canGuestSelfRegister(compact('email'));
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Email_Not_Valid_Should_Throw_Validation_Exception()
    {
        $email = 'johndoe@not-an-email';
        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('The self registration data could not be validated.');
        $this->service->canGuestSelfRegister(compact('email'));
    }

    public function testSelfRegistrationEmailDomainsDryRunService_canGuestSelfRegister_Not_Deleted_User_Already_In_DB_Should_Throw_Forbidden_Exception()
    {
        $email = 'johndoe@passbolt.com';
        $this->setSelfRegistrationSettingsData();
        UserFactory::make()->setField('username', $email)->persist();
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('The email is already registered.');
        $this->service->canGuestSelfRegister(compact('email'));
    }

    public function testSelfRegistrationEmailDomainsDryRunService_Invalid_Data_In_DB_Should_Throw_An_Internal_Error()
    {
        $email = 'johndoe@passbolt.com';
        $this->setSelfRegistrationSettingsData('provider', 'invalid data');
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Could not validate the self registration settings found in database.');
        $this->service->canGuestSelfRegister(compact('email'));
    }
}
