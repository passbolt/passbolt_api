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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryOrganizationPolicies;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryOrganizationPolicySetServiceTest extends AccountRecoveryTestCase
{
    use EmailQueueTrait;

    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new AccountRecoveryOrganizationPolicySetService();
        $this->initEmailForServiceTest(['Passbolt/AccountRecovery']);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->service);
    }

    /**
     * Return valid user access control for the service
     *
     * @return UserAccessControl
     * @throws \Exception
     */
    private function getUac(): UserAccessControl
    {
        return new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
    }

    // Invalid policy error scenarios

    /**
     * Service throws a validation error if not data is provided
     */
    public function testAccountRecoveryOrganizationPolicySetService_PolicyValidation_Error_Empty()
    {
        $cases = [[], ['policy' => null], ['policy' => '']];

        foreach ($cases as $case) {
            try {
                $this->service->set($this->getUac(), $case);
                $this->fail();
            } catch (ValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['policy']['_empty']);
            }
        }
    }

    /**
     * Service throws a validation error if invalid policy is provided
     */
    public function testAccountRecoveryOrganizationPolicySetService_PolicyValidation_Error_Inlist()
    {
        $cases = [['policy' => 'nope'], ['policy' => 'disabled️🔥']];

        foreach ($cases as $case) {
            try {
                $this->service->set($this->getUac(), $case);
                $this->fail();
            } catch (ValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['policy']['inList']);
            }
        }
    }

    // Bad request scenarios

    /**
     * Disabled (not set) => Disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange0()
    {
        try {
            $this->service->set($this->getUac(), ['policy' => 'disabled']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Disabled => Disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange1()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        try {
            $this->service->set($this->getUac(), ['policy' => 'disabled']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Optin => Optin
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange2()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        try {
            $this->service->set($this->getUac(), ['policy' => 'opt-in']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Opt-out => Opt-out
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange3()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optout()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        try {
            $this->service->set($this->getUac(), ['policy' => 'opt-out']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Mandatory => Mandatory
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange4()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        try {
            $this->service->set($this->getUac(), ['policy' => 'mandatory']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Disabled => Enabled with wrong public key data
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_Format()
    {
        $cases = [[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => null,
        ],[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => '',
        ],[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => '🔥',
        ]];

        foreach ($cases as $case) {
            try {
                $this->service->set($this->getUac(), $case);
                $this->fail();
            } catch (BadRequestException $exception) {
                $this->assertTextContains('Invalid request', $exception->getMessage());
            }
        }
    }

    // Public key validation errors

    /**
     * Disabled => Enabled with required fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Required()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_required']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_required']);
        }
    }

    /**
     * Disabled => Enabled with empty fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Empty()
    {
        $cases = [[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => null,
                'armored_key' => null,
            ],
        ], [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '',
                'armored_key' => '',
            ],
        ]];

        foreach ($cases as $case) {
            try {
                $this->service->set($this->getUac(), $case);
                $this->fail();
            } catch (CustomValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_empty']);
                $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_empty']);
            }
        }
    }

    /**
     * Disabled => Enabled with required fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Required2()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_required']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_required']);
        }
    }

    /**
     * Disabled => Enabled fingerprint
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Invalid()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CF',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertSame('The fingerprint should be a string of 40 hexadecimal characters.', $e['account_recovery_organization_public_key']['fingerprint']['invalidFingerprint']);
            $this->assertSame('The fingerprint does not match the one of the armored key.', $e['account_recovery_organization_public_key']['fingerprint']['isMatchingKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled invalid key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Unparsable()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
                    'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFXHTB8BEADAaRMUn++WVatrw3kQK7/6S6DvBauIYcBateuFjczhwEKXUD6T
hLm7nOv5/TKzCpnB5WkP+UZyfT/+jCC2x4+pSgog46jIOuigWBL6Y9F6KkedApFK
xnF6cydxsKxNf/V70Nwagh9ZD4W5ujy+RCB6wYVARDKOlYJnHKWqco7anGhWYj8K
KaDT+7yM7LGy+tCZ96HCw4AvcTb2nXF197Btu2RDWZ/0MhO+DFuLMITXbhxgQC/e
aA1CS6BNS7F91pty7s2hPQgYg3HUaDogTiIyth8R5Inn9DxlMs6WDXGc6IElSfhC
VvGT2KJDT85vR3oNbB0U5wlbKPa+bUl8CokEDjqrDmdZOOs/UO2mc45V3X5RNRtp
NZMBGPJsxOKQExEOZncOVsY7ZqLrecuR8UJBQnhPd1aoz3HCJppaPxL4Q==
=Kwft
-----END PGP PUBLIC KEY BLOCK-----',
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertSame('Could not parse the OpenPGP public key.', $e['account_recovery_organization_public_key']['fingerprint']['isMatchingKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled with non matching fingerprint
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_NoFingerprintMatch()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isMatchingKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled with revoked key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_RevokedKey()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isNotRevokedRule']);
        }
    }

    /**
     * Disabled => Enabled with key including revoked signature
     * (not actually revoked, only a 3rd party signature is revoked)
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Success_RevokedSigKey()
    {
        $this->service->set($this->getUac(), [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '170B1EF744092F6EBB0CDD517D1699F049F3E21B',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'revoked_sig_public.key'),
            ],
        ]);
        $this->assertTrue(true);
    }

    /**
     * Disabled => Enabled with rsa2048 key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_WeakKey()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '26FD986838F4F9AB318FF56AE5DFCEE142949B78',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isValidKeySizeStrictRule']);
        }
    }

    /**
     * Disabled => Enabled with broken key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_BrokenKey()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public_broken.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['canEncrypt']);
        }
    }

    /**
     * Disabled => Enabled with rsa2048 key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_NotRSAKey()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => 'A0F8C364CDBF24A0B08705B9E26A323B3F4E4124',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'elgamal_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isValidAlgorithmStrictRule']);
        }
    }

    /**
     * Disabled => Enabled with expired key / key with expiry date
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ExpiredKey()
    {
        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => 'BD92B8DE3FCF8DD5D60A4DF91E5E3B142396F2C7',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isNotExpiredRule']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['hasNoExpiryDateRule']);
        }
    }

    /**
     * Disabled => Enabled with server key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReuseServerKey()
    {
        $oldF = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $oldP = Configure::read('passbolt.gpg.serverKey.public');

        Configure::write('passbolt.gpg.serverKey.fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        Configure::write('passbolt.gpg.serverKey.armored_key', FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');

        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotServerKeyFingerprintRule']);
        }

        Configure::write('passbolt.gpg.serverKey.fingerprint', $oldF);
        Configure::write('passbolt.gpg.serverKey.fingerprint', $oldP);
    }

    /**
     * Disabled => Enabled with user key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReuseUserKey()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                    'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotUserKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled with previous account recovery key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReusePreviousAccountRecoveryKey()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        try {
            $this->service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotAccountRecoveryOrganizationPublicKeyFingerprintRule']);
        }

        $this->assertEmailQueueIsEmpty();
    }

    // DISABLED => ENABLED
    // SUCCESS SCENARIOS

    /**
     * Disabled => Enabled (Opt-in), first policy ever
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled()
    {
        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);
        $keyData = AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()->getEntity();
        $policyValue = 'opt-in';
        $data = [
            'policy' => $policyValue,
            'account_recovery_organization_public_key' => [
                'fingerprint' => $keyData->fingerprint,
                'armored_key' => $keyData->armored_key,
            ],
        ];
        $policy = $this->service->set($uac, $data);

        $this->assertNotEmpty($policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($uac->getId(), $policy->created_by);
        $this->assertEquals($uac->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($uac->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($uac->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertEquals(1, AccountRecoveryOrganizationPublicKeyFactory::count());

        // Check emails
        $this->assertEmailQueueCount(count($admins));
        foreach ($admins as $admin) {
            if ($admin->id === $user->id) {
                $this->assertEmailInBatchContains("You have set the account recovery organization policy to $policyValue.", $admin->username);
            } else {
                $this->assertEmailInBatchContains($user->profile->first_name . " has set the account recovery organization policy to $policyValue.", $admin->username);
            }
            $this->assertEmailInBatchContains("The fingerprint of the organization public key is $keyData->fingerprint.", $admin->username);
        }
    }

    /**
     * Disabled => Enabled (Mandatory), previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled2()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);
        $keyData = AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()->getEntity();
        $policyValue = 'mandatory';

        $data = [
            'policy' => $policyValue,
            'account_recovery_organization_public_key' => [
                'fingerprint' => $keyData->fingerprint,
                'armored_key' => $keyData->armored_key,
            ],
        ];
        $policy = $this->service->set($uac, $data);

        $this->assertEquals('mandatory', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($uac->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($uac->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($uac->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::find()->where(['deleted IS' => null])->count());
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::find()->where(['deleted IS NOT' => null])->count());

        $this->assertEquals(1, AccountRecoveryOrganizationPublicKeyFactory::count());

        // Check emails
        $this->assertEmailQueueCount(count($admins));
        foreach ($admins as $admin) {
            if ($admin->id === $user->id) {
                $this->assertEmailInBatchContains("You have set the account recovery organization policy to $policyValue.", $admin->username);
            } else {
                $this->assertEmailInBatchContains($user->profile->first_name . " has set the account recovery organization policy to $policyValue.", $admin->username);
            }
            $this->assertEmailInBatchContains("The fingerprint of the organization public key is $keyData->fingerprint.", $admin->username);
            $this->assertEmailInBatchContains('Account Recovery Enabled', $admin->username);
        }
    }

    // ENABLED => DISABLED
    // SUCCESS SCENARIOS

    /**
     * Enabled => Disabled, previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_EnabledDisabled()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);
        $keyData = AccountRecoveryOrganizationPublicKeyFactory::make()->revokedKey()->getEntity();
        $policyValue = 'disabled';

        $data = [
            'policy' => $policyValue,
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => $keyData->fingerprint,
                'armored_key' => $keyData->armored_key,
            ],
        ];

        $policy = $this->service->set($uac, $data);

        $this->assertNotEmpty($policy);
        $this->assertEquals('disabled', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($uac->getId(), $policy->created_by);
        $this->assertEquals($uac->getId(), $policy->modified_by);
        $this->assertEmpty($policy->account_recovery_organization_public_key);

        // There should be two policies and one deleted
        $this->assertSame(2, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::find()->where(['deleted IS NOT' => null])->count());

        // There should be two public keys in DB both deleted
        $this->assertSame(2, AccountRecoveryOrganizationPublicKeyFactory::count());
        $this->assertEquals(2, AccountRecoveryOrganizationPublicKeyFactory::find()->where(['deleted IS NOT' => null])->count());

        // There should be one account recovery request as rejected
        $this->assertEquals(1, AccountRecoveryRequestFactory::find()->where(['status' => 'rejected'])->count());
        $this->assertEquals(1, AccountRecoveryRequestFactory::find()->count());

        // Private key and passwords table and user settings should be truncated
        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(0, AccountRecoveryUserSettingFactory::count());

        // Check emails
        $this->assertEmailQueueCount(count($admins));
        foreach ($admins as $admin) {
            if ($admin->id === $user->id) {
                $this->assertEmailInBatchContains('You have disabled the account recovery.', $admin->username);
            } else {
                $this->assertEmailInBatchContains($user->profile->first_name . ' has disabled the account recovery.', $admin->username);
            }
            $this->assertEmailInBatchContains('Account Recovery Disabled', $admin->username);
        }
    }

    // ENABLED => ENABLED
    // SIMPLE SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateSimple()
    {
        $this->startScenarioOptinNoBackups();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);
        $policyValue = AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT;

        $policy = $this->service->set($uac, [
            'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
            'public_key_id' => UuidFactory::uuid('acr.org_public_key.id'),
        ]);

        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY);

        sleep(1); // needed to avoid creation time to get mixed up because they become equal
        $this->assertEmailQueueCount(count($admins));
        $this->deleteEmailQueue(); // We focus here on the coming emails, generated by the update

        $policy = $this->service->set($uac, [
            'policy' => $policyValue,
            'public_key_id' => UuidFactory::uuid('acr.org_public_key.id'),
        ]);
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT);

        // Public key should remain the same
        $this->assertNotEmpty($policy->public_key_id);
        $this->assertEquals(UuidFactory::uuid('acr.org_public_key.id'), $policy->public_key_id);

        // There should be 3 policies, one not deleted
        $this->assertEquals(3, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertEquals(1, AccountRecoveryOrganizationPolicyFactory::find()->where(['deleted IS' => null])->count());

        // There should be one public key
        $this->assertEquals(1, AccountRecoveryOrganizationPublicKeyFactory::count());
        $this->assertEquals(1, AccountRecoveryOrganizationPublicKeyFactory::find()->where(['deleted IS' => null])->count());

        // Check emails
        $this->assertEmailQueueCount(count($admins));
        foreach ($admins as $admin) {
            if ($admin->id === $user->id) {
                $this->assertEmailInBatchContains("You have updated the account recovery organization policy to $policyValue.", $admin->username);
            } else {
                $this->assertEmailInBatchContains($user->profile->first_name . " has updated the account recovery organization policy to $policyValue.", $admin->username);
            }
            $this->assertEmailInBatchContains('Account Recovery Updated', $admin->username);
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateSimple_InvalidPolicy()
    {
        $this->startScenarioOptinNoBackups();
        try {
            $this->service->set($this->getUac(), ['policy' => 'invalid']);
            $this->fail();
        } catch (ValidationException $exception) {
            $this->assertTrue(true);
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateSimple_MissingPublicKeyId()
    {
        $this->startScenarioOptinNoBackups();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);

        try {
            $this->service->set($uac, [
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
                'public_key_id' => UuidFactory::uuid(),
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['public_key_id']['notCurrentPublicKeyId']);
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateSimple_WrongPublicKeyId()
    {
        $this->startScenarioOptinNoBackups();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);

        try {
            $this->service->set($uac, [
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['public_key_id']['_required']);
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateSimple_PublicKeyIdNotUuid()
    {
        $this->startScenarioOptinNoBackups();

        /** @var \App\Model\Entity\User[] $admins */
        $admins = UserFactory::make(3)->active()->admin()->persist();
        $user = $admins[0];
        $uac = $this->makeUac($user);

        try {
            $this->service->set($uac, [
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
                'public_key_id' => 'nope',
            ]);
            $this->fail();
        } catch (ValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['public_key_id']['uuid']);
        }
    }

    // SIMPLE ROTATION SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateWithKeyRotationSimple()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        try {
            $policy = $this->service->set($this->getUac(), $data);
        } catch (ValidationException | CustomValidationException $exception) {
            $this->fail();
        }

        $this->assertEquals(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN, $policy->policy);
        $this->assertEquals('23C6C30E241324C90A44A719A86A7EA3739797F5', $policy->account_recovery_organization_public_key->fingerprint);

        // There should be 2 policies, one not deleted
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->assertEquals(2, $table->find()->count());
        $this->assertEquals(1, $table->find()->where(['deleted IS' => null])->count());
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_RevokedKeyMissing()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6C30E241324C90A44A719A86A7EA3739797F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('Revoked key is required', $exception->getMessage());
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_RevokedKeyNotRevoked()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6C30E241324C90A44A719A86A7EA3739797F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $this->assertTextContains('Could not validate key revocation.', $exception->getMessage());
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_organization_revoked_key']['armored_key']['isRevokedRule']));
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_NewKeyReused()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            ],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTextContains('Could not validate policy data.', $exception->getMessage());
            $this->assertTrue(isset($e['account_recovery_organization_public_key']['fingerprint']['isNotUserKeyFingerprintRule']));
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_NewKeyMissing()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('New key is required', $exception->getMessage());
        }
    }

    // FULL ROTATION SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateWithFullRotation()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprintClean = '23C6C30E241324C90A44A719A86A7EA3739797F5';
        $newKeyFingerprint = '23c6 c30e 2413 24c9 0a44  a719 a86a 7ea3 7397 97f5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprintClean);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BF FCB7 B74A F4C8 5E81  AB26 5088 5052 5CD7 8BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $policy = $this->service->set($this->getUac(), $data);
        } catch (CustomValidationException $exception) {
            $this->fail(json_encode($exception->getErrors()));
        }

        $this->assertEquals('opt-out', $policy->policy);
        $this->assertEquals('23C6C30E241324C90A44A719A86A7EA3739797F5', $policy->account_recovery_organization_public_key->fingerprint);

        // There should be 2 policies, one not deleted
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->assertEquals(2, $table->find()->count());
        $this->assertEquals(1, $table->find()->where(['deleted IS' => null])->count());
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsPrivateKeyNotFound()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.betty.id'), // betty not exist
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['private_key_id']['_existsIn']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsMissing()
    {
        $this->startScenarioOptinWith2Backups();

        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);
        $msg = $gpg->encrypt('cofveve');

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.betty.id'), // ada missing
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $msg,
            ],[
                'private_key_id' => UuidFactory::uuid('acr.private_key.betty.id'), // betty doubled
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $msg,
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords']['missingPasswordForPrivateKeyIds'][0]));
            $missingId = $e['account_recovery_private_key_passwords']['missingPasswordForPrivateKeyIds'][0];
            $this->assertEquals(UuidFactory::uuid('acr.private_key.ada.id'), $missingId);
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsFormatInvalid_NotParsable()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => 'not a gpg message',
            ]],
        ];

        try {
            $policy = $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['data']['isValidOpenPGPMessage']));
        }

        // Check the policy has not changed
        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsFormatInvalid_Symetric()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $oldKeyRevokedArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $oldKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $oldKeyFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => $oldKeyFingerprint,
                'armored_key' => $oldKeyRevokedArmored,
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password.msg') ,
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['data']['hasAsymmetricPacketRule']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_PasswordPrivateKeyMissing()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BF FCB7 B74A F4C8 5E81  AB26 5088 5052 5CD7 8BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['private_key_id']['_required']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_PasswordPrivateKeyInvalid()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BF FCB7 B74A F4C8 5E81  AB26 5088 5052 5CD7 8BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => 'nope',
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['private_key_id']['uuid']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_PasswordPrivateKeyNotFound()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BF FCB7 B74A F4C8 5E81  AB26 5088 5052 5CD7 8BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid(),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['private_key_id']['_existsIn']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_RecipientInvalid()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $oldKeyRevokedArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $oldKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $oldKeyFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        // Use old key instead of new one
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($oldKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($oldKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => $oldKeyFingerprint,
                'armored_key' => $oldKeyRevokedArmored,
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['data']['wrongRecipient']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_RecipientTooMany()
    {
        $this->startScenarioOptinWithBackupAndRequest();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);
        $data = $gpg->encrypt('cofveve');

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $data,
            ],[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'data' => $data,
            ]],
        ];

        try {
            $this->service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords']['invalidPasswordCount']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    // SCENARIOS

    /**
     * Setup needed fixtures to have a basic scenario with
     *
     * @throws \Exception
     */
    private function startScenarioOptinWithBackupAndRequest()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
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

        AccountRecoveryOrganizationPublicKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.org_public_key_old.id'))
            ->setField('fingerprint', '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F')
            ->setField('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'old_revoked_public.key'))
            ->setField('deleted', Chronos::now()->subDay())
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'rejected')
            ->setField('user_id', UuidFactory::uuid('user.id.betty'))
            ->persist();

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
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();
    }

    /**
     * Setup needed fixtures to have a basic scenario with
     *
     * @throws \Exception
     */
    private function startScenarioOptinWith2Backups()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
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

        AccountRecoveryOrganizationPublicKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.org_public_key_old.id'))
            ->setField('fingerprint', '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F')
            ->setField('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'old_revoked_public.key'))
            ->setField('deleted', Chronos::now()->subDay())
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', UuidFactory::uuid('user.id.betty'))
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.betty.id'))
            ->setField('user_id', UuidFactory::uuid('user.id.betty'))
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY)
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.betty.id'))
            ->setField('recipient_fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY)
            ->persist();
    }

    /**
     * Setup needed fixtures to have a basic scenario with no passwords
     *
     * @throws \Exception
     */
    private function startScenarioOptinNoBackups()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
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
    }
}
