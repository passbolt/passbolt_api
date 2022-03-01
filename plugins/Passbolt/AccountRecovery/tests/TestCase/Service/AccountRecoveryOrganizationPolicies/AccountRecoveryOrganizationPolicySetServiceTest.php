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
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryOrganizationPolicySetServiceTest extends AccountRecoveryTestCase
{
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
        $service = new AccountRecoveryOrganizationPolicySetService();

        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
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
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
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
        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'disabled']);
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

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'disabled']);
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

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'opt-in']);
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

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'opt-out']);
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

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'mandatory']);
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
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CF',
                    'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----',
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['invalidFingerprint']);
        }
    }

    /**
     * Disabled => Enabled invalid key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Unparsable()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['invalidArmoredKey']);
        }
    }

    /**
     * Disabled => Enabled with non matching fingerprint
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_NoFingerprintMatch()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
        $service = new AccountRecoveryOrganizationPolicySetService();
        $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
     * Disabled => Enabled with expired key / key with expiry date
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ExpiredKey()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
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
    }

    // DISABLED => ENABLED
    // SUCCESS SCENARIOS

    /**
     * Disabled => Enabled (Opt-in), first policy ever
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled()
    {
        $service = new AccountRecoveryOrganizationPolicySetService();
        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
        ];
        $policy = $service->set($this->getUac(), $data);

        $this->assertNotEmpty($policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $foundPolicies = $table->find()->all();
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $foundKeys = $table->find()->all();
        $this->assertEquals(1, count($foundPolicies));
        $this->assertEquals(1, count($foundKeys));
    }

    /**
     * Disabled => Enabled (Mandatory), previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled2()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'old_revoked_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        $data = [
            'policy' => 'mandatory',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
        ];
        $policy = $service->set($this->getUac(), $data);

        $this->assertEquals('mandatory', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $foundPolicies = $table->find()->all();
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $foundKeys = $table->find()->all();
        $this->assertEquals(1, count($foundPolicies));
        $this->assertEquals(2, count($foundKeys));
    }

    // ENABLED => DISABLED
    // SUCCESS SCENARIOS
    /**
     * Enabled => Disabled, previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_EnabledDisabled_NoPassword()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        $data = [
            'policy' => 'disabled',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
        ];

        $policy = $service->set($this->getUac(), $data);
        $this->assertNotEmpty($policy);
        $this->assertEquals('disabled', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);

        $this->assertEmpty($policy->account_recovery_organization_public_key);
    }
}
