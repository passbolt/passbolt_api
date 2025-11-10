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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\I18n\DateTime;
use Passbolt\Metadata\Service\Healthcheck\UserFriendly\ServerMissingAccessToMetadataKeyHealthcheck;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @covers \Passbolt\Metadata\Service\Healthcheck\UserFriendly\ServerMissingAccessToMetadataKeyHealthcheck
 */
class ServerMissingAccessToMetadataKeyHealthcheckTest extends AppTestCaseV5
{
    private ?ServerMissingAccessToMetadataKeyHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ServerMissingAccessToMetadataKeyHealthcheck();
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

    public function testServeMissingAccessToMetadataKeyHealthcheck_Success(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->serverKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('isServerHasAccessToMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testServeMissingAccessToMetadataKeyHealthcheck_Success_SkippedWhenInZeroKnowledgeMode(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isSkipped());
    }

    public function testServeMissingAccessToMetadataKeyHealthcheck_Error_NoMetadataPrivateKeys(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertStringContainsString('The server does not have access to metadata key', $this->sut->getFailureMessage());
    }

    public function testServeMissingAccessToMetadataKeyHealthcheck_Error_ServerMissingMetadataPrivateKeys(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertStringContainsString('The server does not have access to metadata key', $this->sut->getFailureMessage());
    }

    public function testServeMissingAccessToMetadataKeyHealthcheck_Fail_MultipleKeys(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $user = UserFactory::make()->active()->admin()->persist();
        // metadata key 1 with private key
        $metadataKey1 = MetadataKeyFactory::make(['created' => DateTime::now()->subDays(2)])->withServerKey()->withServerPrivateKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey1)->withUser($user)->persist();
        // metadata key 2 without private key
        $metadataKey2 = MetadataKeyFactory::make(['created' => DateTime::now()->subDays(5)])->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey2)->withUser($user)->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
    }

    public function testServeMissingAccessToMetadataKeyHealthcheck_Fail_ExpiredKey(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withServerPrivateKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($user)->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->expired()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($user)->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
    }
}
