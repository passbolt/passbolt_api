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
 * @since         5.4.0
 */
namespace Passbolt\Metadata\Test\TestCase\Service\Healthcheck\UserFriendly;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Metadata\Service\Healthcheck\UserFriendly\NoActiveMetadataKeyHealthcheck;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @covers \Passbolt\Metadata\Service\Healthcheck\UserFriendly\NoActiveMetadataKeyHealthcheck
 */
class NoActiveMetadataKeyHealthcheckTest extends AppTestCaseV5
{
    private ?NoActiveMetadataKeyHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new NoActiveMetadataKeyHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        OpenPGPBackendFactory::reset();

        parent::tearDown();
    }

    public function testNoActiveMetadataKeyHealthcheck_Pass_MetadataEnabled_ActiveKeyPresent(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        // active
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withServerPrivateKey()->persist();
        // expired
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->expired();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withServerPrivateKey()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('noActiveMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testNoActiveMetadataKeyHealthcheck_Pass_MetadataDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('noActiveMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testNoActiveMetadataKeyHealthcheck_Fail_NoMetadataKey(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('noActiveMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testNoActiveMetadataKeyHealthcheck_Fail_MetadataKeyExpired(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->expired();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withServerPrivateKey()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('noActiveMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testNoActiveMetadataKeyHealthcheck_Fail_MetadataKeyDeleted(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->deleted();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withServerPrivateKey()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->expired();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withServerPrivateKey()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('noActiveMetadataKey', $this->sut->getLegacyArrayKey());
    }
}
