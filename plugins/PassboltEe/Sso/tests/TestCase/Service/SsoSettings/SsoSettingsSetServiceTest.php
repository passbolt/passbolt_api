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
     * New entity success
     *
     * @return void
     */
    public function testSsoSettingsSetServiceSuccess(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => 'https://login.microsoftonline.com',
                'client_id' => UuidFactory::uuid(),
                'tenant_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'client_secret_expiry' => Chronos::now()->addDays(365),
                'prompt' => SsoSettingsAzureDataForm::PROMPT_NONE,
                'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_UPN,
            ],
        ];

        $dto = $this->service->create($uac, $data);

        // Check returned object is correctly formatted
        $this->assertTrue(Validation::uuid($dto->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $dto->getProvider());
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $dto->getProviders());
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $dto->status);
        $this->assertEquals($data['data'], $dto->getData()->toArray());

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
        $this->assertEquals($data['data'], $decryptedData);
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
