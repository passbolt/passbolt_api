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
namespace Passbolt\Sso\Test\Lib;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Lib\Utility\ErrorIntegrationTestTrait;
use App\Test\Lib\Utility\JsonRequestTrait;
use App\Test\Lib\Utility\LoginTestTrait;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsSetService;

class SsoIntegrationTestCase extends SsoTestCase
{
    use IntegrationTestTrait;
    use JsonRequestTrait;
    use LoginTestTrait;
    use ErrorIntegrationTestTrait;
    use MockAzureResourceOwnerTrait;

    public const IP_ADDRESS = '127.0.0.1';
    public const USER_AGENT = 'phpunit';

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();

        // setup IP address and user agent for requests
        $this->configRequest(['environment' => [
            'REMOTE_ADDR' => self::IP_ADDRESS,
            'HTTP_USER_AGENT' => self::USER_AGENT,
        ]]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @param User $admin user entity
     * @param string|null $status status
     * @return SsoSettingsDto
     */
    public function createAzureSettingsFromConfig(
        User $admin,
        ?string $status = SsoSetting::STATUS_ACTIVE,
        $options = []
    ): SsoSettingsDto {
        $azureConfig = Configure::read('passbolt.selenium.sso.active');
        if (!isset($azureConfig)) {
            $this->markTestSkipped('Selenium SSO is set to inactive, skipping tests.');
        }

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => Configure::read('passbolt.selenium.sso.azure.url'),
                'client_id' => Configure::read('passbolt.selenium.sso.azure.clientId'),
                'tenant_id' => Configure::read('passbolt.selenium.sso.azure.tenantId'),
                'client_secret' => Configure::read('passbolt.selenium.sso.azure.secretId'),
                'client_secret_expiry' => new FrozenTime(Configure::read('passbolt.selenium.sso.azure.secretExpiry')),
                'prompt' => $options['prompt'] ?? SsoSettingsAzureDataForm::PROMPT_LOGIN,
                'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
            ],
        ];

        try {
            // create a correct setting using the service
            (new SsoSettingsSetService())->create($uac, $data);
            $ssoSettingsTable = $this->fetchTable('Passbolt/Sso.SsoSettings');
            // activate it the fast way
            /** @var SsoSetting $setting */
            $setting = $ssoSettingsTable->find()->firstOrFail();
            $setting->status = $status;
            $ssoSettingsTable->save($setting);
        } catch (CustomValidationException $exception) {
            $this->fail('Config passbolt.selenium.sso.azure is invalid.');
        }

        /** @var SsoSettingsDto $dto */
        $dto = (new SsoSettingsGetService())->getByIdOrFail($setting->id);

        return $dto;
    }

    /**
     * @param User $admin User entity (mostly this will be with "admin" role)
     * @param string|null $status Status.
     * @return SsoSettingsDto
     */
    public function createGoogleSettingsFromConfig(User $admin, ?string $status = SsoSetting::STATUS_ACTIVE): SsoSettingsDto
    {
        $seleniumSsoConfig = Configure::read('passbolt.selenium.sso.active');
        if (!isset($seleniumSsoConfig) || !$seleniumSsoConfig) {
            $this->markTestSkipped('Selenium SSO is set to inactive, skipping tests.');
        }

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'provider' => SsoSetting::PROVIDER_GOOGLE,
            'data' => [
                'client_id' => Configure::read('passbolt.selenium.sso.google.clientId'),
                'client_secret' => Configure::read('passbolt.selenium.sso.google.secretId'),
            ],
        ];

        try {
            // create a correct setting using the service
            (new SsoSettingsSetService())->create($uac, $data);
            $ssoSettingsTable = $this->fetchTable('Passbolt/Sso.SsoSettings');
            // activate it the fast way
            /** @var SsoSetting $setting */
            $setting = $ssoSettingsTable->find()->firstOrFail();
            $setting->status = $status;
            $ssoSettingsTable->save($setting);
        } catch (CustomValidationException $exception) {
            $this->fail('Config passbolt.selenium.sso.google is invalid.');
        }

        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsDto $dto */
        $dto = (new SsoSettingsGetService())->getByIdOrFail($setting->id);

        return $dto;
    }
}
