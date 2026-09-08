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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Test\TestCase\Authenticator;

use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\I18n\Date;
use Cake\Log\Log;
use Passbolt\Scim\Authenticator\ScimTokenAuthenticator;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * @covers \Passbolt\Scim\Authenticator\ScimTokenAuthenticator
 */
class ScimTokenAuthenticatorTest extends ScimApiIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->createSettingsEntry = false;
        parent::setUp();
        $this->settingId = ScimSettingFactory::SCIM_TEST_SETTING_ID;
        // Enable event tracking for emails
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->createSettingsEntry = true;
        parent::tearDown();
    }

    public function testScimTokenAuthenticator_ExpiredAndEnforced_DeniesAccess(): void
    {
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, true);
        $expiredDate = Date::now()->modify('-1 day')->format('Y-m-d');
        ScimSettingFactory::make()->default(['expired' => $expiredDate])->persist();

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(401);
    }

    public function testScimTokenAuthenticator_ExpiredAndNotEnforced_Returns200AndLogsWarning(): void
    {
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, false);
        Log::setConfig('scim_test_warning', ['className' => 'Array', 'levels' => ['warning']]);
        $expiredDate = Date::now()->modify('-1 day')->format('Y-m-d');
        ScimSettingFactory::make()->default(['expired' => $expiredDate])->persist();

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(200);
        /** @var \Cake\Log\Engine\ArrayLog $logger */
        $logger = Log::engine('scim_test_warning');
        $logs = $logger->read();
        Log::drop('scim_test_warning');
        $this->assertTrue($this->logsContain($logs, 'The SCIM secret token is expired'));
    }

    public function testScimTokenAuthenticator_NotExpired_Returns200AndNoWarning(): void
    {
        ScimSettingFactory::make()->default()->persist();
        Log::setConfig('scim_test_warning', ['className' => 'Array', 'levels' => ['warning']]);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));
        $this->assertResponseCode(200);

        /** @var \Cake\Log\Engine\ArrayLog $logger */
        $logger = Log::engine('scim_test_warning');
        $logs = $logger->read();
        Log::drop('scim_test_warning');
        $this->assertFalse($this->logsContain($logs, 'The SCIM secret token is expired'));
    }

    public function testScimTokenAuthenticator_Expired_DispatchesExpiredEvent(): void
    {
        $expiredDate = Date::now()->modify('-1 day')->format('Y-m-d');
        ScimSettingFactory::make()->default(['expired' => $expiredDate])->persist();
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, false);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertEventFiredWith(ScimTokenAuthenticator::EVENT_TOKEN_EXPIRED, 'expired', $expiredDate);
    }

    public function testScimTokenAuthenticator_NearExpiry_DispatchesNearExpiryEvent(): void
    {
        $nearExpiryDate = Date::now()->modify('+5 days')->format('Y-m-d');
        ScimSettingFactory::make()->default(['expired' => $nearExpiryDate])->persist();
        Configure::write(ScimTokenExpiryChecker::CONFIG_WARNING_WINDOW, '14 days');

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(200);
        $this->assertEventFiredWith(ScimTokenAuthenticator::EVENT_TOKEN_NEAR_EXPIRY, 'expired', $nearExpiryDate);
    }

    public function testScimTokenAuthenticator_WrongToken_DeniesAccess(): void
    {
        // The expiry date is irrelevant here, the token never verifies
        ScimSettingFactory::make()->default()->persist();
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, true);
        $this->configRequest(['headers' => ['Authorization' => 'Bearer wrong-token']]);

        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(401);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * @param array $logs The captured log lines.
     * @param string $needle The text to find.
     * @return bool
     */
    private function logsContain(array $logs, string $needle): bool
    {
        foreach ($logs as $log) {
            if (str_contains((string)$log, $needle)) {
                return true;
            }
        }

        return false;
    }
}
