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
namespace Passbolt\Scim\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryWarningHealthcheck;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

/**
 * @covers \Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryWarningHealthcheck
 */
class ScimSecretTokenExpiryWarningHealthcheckTest extends AppTestCase
{
    private ScimSecretTokenExpiryWarningHealthcheck $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ScimSecretTokenExpiryWarningHealthcheck();
        Configure::write(ScimTokenExpiryChecker::CONFIG_WARNING_WINDOW, '14 days');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testScimSecretTokenExpiryWarningHealthcheck_Warns_TokenNearExpiry(): void
    {
        ScimSettingFactory::make()->default(['expired' => Date::now()->modify('+5 days')->format('Y-m-d')])->persist();

        $result = $this->service->check();

        $this->assertFalse($result->isPassed());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SCIM, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SCIM, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $result->level());
        $this->assertSame('isScimTokenNotNearExpiry', $result->getLegacyArrayKey());
        $this->assertSame(
            __('The SCIM secret token is not about to expire.'),
            $result->getSuccessMessage(),
        );
        $this->assertSame(
            __('The SCIM secret token will expire soon, you are requested to rotate it.'),
            $result->getFailureMessage(),
        );
    }

    public function testScimSecretTokenExpiryWarningHealthcheck_Pass_TokenFarFromExpiry(): void
    {
        ScimSettingFactory::make()->default(['expired' => Date::now()->modify('+100 days')->format('Y-m-d')])->persist();

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertSame(
            __('The SCIM secret token is not about to expire.'),
            $result->getSuccessMessage(),
        );
    }

    public function testScimSecretTokenExpiryWarningHealthcheck_Pass_TokenAlreadyExpired(): void
    {
        // Handled by other healthcheck
        ScimSettingFactory::make()->default(['expired' => Date::now()->modify('-1 day')->format('Y-m-d')])->persist();

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
    }

    public function testScimSecretTokenExpiryWarningHealthcheck_Pass_NoSettings(): void
    {
        $result = $this->service->check();
        $this->assertTrue($result->isPassed());
    }

    public function testScimSecretTokenExpiryWarningHealthcheck_Pass_NoExpiredField(): void
    {
        // Expired field not present in the DB
        ScimSettingFactory::make()->default(['expired' => null])->persist();

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
    }
}
