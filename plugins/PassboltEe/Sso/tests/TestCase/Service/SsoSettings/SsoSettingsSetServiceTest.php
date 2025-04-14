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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\SsoSettings;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Validation\Validation;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Providers\SsoActiveProvidersGetService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsSetService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers \Passbolt\Sso\Service\SsoSettings\SsoSettingsSetService
 */
class SsoSettingsSetServiceTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Service\SsoSettings\SsoSettingsSetService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoSettingsSetService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * @see testSsoSettingsSetService_Success
     * @return array[]
     */
    public function ssoSettingsValidDataProvider()
    {
        $expiry = Chronos::now()->addDays(365);

        return [
            'Azure (default)' => [
                'input' => [
                    'provider' => SsoSetting::PROVIDER_AZURE,
                    'data' => [
                        'url' => 'https://login.microsoftonline.com',
                        'client_id' => 'feca242d-0920-4b22-b4c1-592d4eb69953',
                        'tenant_id' => '49e49094-4fae-4400-85aa-f71e2cdd250b',
                        'client_secret' => '3994735b-54d2-40ba-a324-6153f90c85e3',
                        'client_secret_expiry' => $expiry,
                    ],
                ],
                'expected data' => [
                    'url' => 'https://login.microsoftonline.com',
                    'client_id' => 'feca242d-0920-4b22-b4c1-592d4eb69953',
                    'tenant_id' => '49e49094-4fae-4400-85aa-f71e2cdd250b',
                    'client_secret' => '3994735b-54d2-40ba-a324-6153f90c85e3',
                    'client_secret_expiry' => $expiry->toDateTimeString(),
                    // defaults
                    'prompt' => SsoSettingsAzureDataForm::PROMPT_LOGIN,
                    'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
                    'login_hint' => SsoSettingsAzureDataForm::AZURE_LOGIN_HINT_ENABLED,
                ],
            ],
            'Azure (with advanced settings - optional)' => [
                'input' => [
                    'provider' => SsoSetting::PROVIDER_AZURE,
                    'data' => [
                        'url' => 'https://login.microsoftonline.com',
                        'client_id' => 'feca242d-0920-4b22-b4c1-592d4eb69953',
                        'tenant_id' => '49e49094-4fae-4400-85aa-f71e2cdd250b',
                        'client_secret' => '3994735b-54d2-40ba-a324-6153f90c85e3',
                        'client_secret_expiry' => $expiry,
                        'prompt' => SsoSettingsAzureDataForm::PROMPT_NONE,
                        'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_PREFERRED_USERNAME,
                        'login_hint' => SsoSettingsAzureDataForm::AZURE_LOGIN_HINT_DISABLED,
                    ],
                ],
                'expected data' => [
                    'url' => 'https://login.microsoftonline.com',
                    'client_id' => 'feca242d-0920-4b22-b4c1-592d4eb69953',
                    'tenant_id' => '49e49094-4fae-4400-85aa-f71e2cdd250b',
                    'client_secret' => '3994735b-54d2-40ba-a324-6153f90c85e3',
                    'client_secret_expiry' => $expiry->toDateTimeString(),
                    'prompt' => SsoSettingsAzureDataForm::PROMPT_NONE,
                    'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_PREFERRED_USERNAME,
                    'login_hint' => SsoSettingsAzureDataForm::AZURE_LOGIN_HINT_DISABLED,
                ],
            ],
        ];
    }

    /**
     * New entity success
     *
     * @param array $input Data to test.
     * @param array $expectedData Data to assert.
     * @return void
     * @dataProvider ssoSettingsValidDataProvider
     */
    public function testSsoSettingsSetService_Success(array $input, array $expectedData): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);

        $dto = $this->service->create($uac, $input);

        // Check returned object is correctly formatted
        $this->assertTrue(Validation::uuid($dto->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $dto->getProvider());
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $dto->getProviders());
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $dto->status);
        $this->assertEquals($expectedData, $dto->getData()->toArray());

        // Check database record is encrypted
        $this->assertEquals(1, SsoSettingsFactory::count());
        /** @var SsoSetting $ssoSettingEntity */
        $ssoSettingEntity = SsoSettingsFactory::find()->first();
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $ssoSettingEntity->provider);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $ssoSettingEntity->status);
        $this->assertTextContains('-----BEGIN PGP MESSAGE-----', $ssoSettingEntity->data);

        // Check decrypted data is correct
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        $decryptedData = json_decode($gpg->decrypt($ssoSettingEntity->data), true);
        $this->assertEquals(json_decode(json_encode($expectedData), true), $decryptedData);
    }

    /**
     * Test that the urls are trimmed of and protocol is added
     */
    public function testSsoSettingsSetServiceSuccessMassageUrl(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => 'login.microsoftonline.com/',
                'client_id' => UuidFactory::uuid(),
                'tenant_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'client_secret_expiry' => Chronos::now()->addDays(365),
                'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_UPN,
            ],
        ];

        $dto = $this->service->create($uac, $data);

        $this->assertNotEmpty($dto->getData());
        $dtoData = $dto->getData()->toArray();
        $this->assertNotEmpty($dtoData);
        $this->assertEquals('https://login.microsoftonline.com', $dtoData['url']);
    }

    public function testSsoSettingsSetService_ErrorThrowsCustomValidationException(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [], // Data is empty
        ];

        $this->expectException(CustomValidationException::class);

        $this->service->create($uac, $data);
    }
}
