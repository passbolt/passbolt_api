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
 * @since         5.7.0
 */
namespace Passbolt\Metadata\Test\TestCase\Service\Healthcheck\ZeroKnowledge;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Metadata\Service\Healthcheck\ZeroKnowledge\ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @covers \Passbolt\Metadata\Service\Healthcheck\ZeroKnowledge\ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck
 */
class ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheckTest extends AppTestCaseV5
{
    private ?ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck();
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

    public function testServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck_Success_ZeroKnowledgeOnWithNoServerKey(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertFalse($this->sut->isSkipped());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('isServerMetadataKeyAccessInZeroKnowledgeMode', $this->sut->getLegacyArrayKey());
        $this->assertStringContainsString('The server does not have access to the server metadata private key in Zero-knowledge mode', $this->sut->getSuccessMessage());
    }

    public function testServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck_Error_ServerKeyPresentWhenInZeroKnowledgeMode(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->serverKey()->persist(); // server private key present
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $this->assertFalse($this->sut->isSkipped());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('isServerMetadataKeyAccessInZeroKnowledgeMode', $this->sut->getLegacyArrayKey());
        $this->assertStringContainsString(
            'The server has access to the server metadata private key while in Zero-knowledge mode',
            $this->sut->getFailureMessage()
        );
        $this->assertStringContainsString(
            'When Zero-knowledge mode is enabled, the server should not have access to the server metadata private key',
            $this->sut->getHelpMessage()
        );
    }

    public function testServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck_Skip_V5Disabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isSkipped());
    }

    public function testServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck_Skip_ZeroKnowledgeOff(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isSkipped());
    }
}
