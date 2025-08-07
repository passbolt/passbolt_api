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
namespace Passbolt\Metadata\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\I18n\DateTime;
use Passbolt\Metadata\Service\Healthcheck\ServerPrivateMetadataKeyValidateHealthcheck;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\Healthcheck\ServerPrivateMetadataKeyValidateHealthcheck
 */
class ServerPrivateMetadataKeyValidateHealthcheckTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private ?ServerPrivateMetadataKeyValidateHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ServerPrivateMetadataKeyValidateHealthcheck();
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

    public function testServerPrivateMetadataKeyValidateHealthcheck_Success(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withServerPrivateKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('canValidatePrivateMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testServerPrivateMetadataKeyValidateHealthcheck_Pass_MultipleKeys(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $metadataKey1 = MetadataKeyFactory::make()->withServerKey()->withServerPrivateKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey1)->withUser($user)->persist();
        $metadataKey2 = MetadataKeyFactory::make()->withServerKey()->withServerPrivateKey()->expired()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey2)->withUser($user)->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('canValidatePrivateMetadataKey', $this->sut->getLegacyArrayKey());
    }

    public function testServerPrivateMetadataKeyValidateHealthcheck_Pass_MetadataServerKeyCreatedByUser(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $metadataKey = MetadataKeyFactory::make()
            ->with('MetadataPrivateKeys', MetadataPrivateKeyFactory::make()->withServerPrivateKey()->withCreatorAndModifier($user))
            ->withServerKey()
            ->withCreatorAndModifier()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($user)->persist();

        $this->sut->check();

        $this->assertTrue($this->sut->isPassed());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_METADATA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $this->sut->level());
        $this->assertSame('canValidatePrivateMetadataKey', $this->sut->getLegacyArrayKey());
    }

    /**
     * `backupStaticProperties` here is required to not pollute `$keycache` state for other test cases in this file.
     *
     * @backupStaticProperties enabled
     * @return void
     */
    public function testServerPrivateMetadataKeyValidateHealthcheck_Fail_UnableToDecrypt(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg');
        $metadataPrivateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $armoredMessage,
            'user_id' => null,
        ]);
        $metadataKey = MetadataKeyFactory::make()
            ->with('MetadataPrivateKeys', $metadataPrivateKey)
            ->withServerKey()
            ->withCreatorAndModifier()
            ->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $expectedMsg = 'The server metadata private key is not valid.';
        $expectedMsg .= ' ' . sprintf('Unable to decrypt the metadata private key (id: %s) data', $metadataKey->metadata_private_keys[0]->get('id'));
        $this->assertStringContainsString($expectedMsg, $this->sut->getFailureMessage());
    }

    /**
     * @backupStaticProperties enabled
     * @return void
     */
    public function testServerPrivateMetadataKeyValidateHealthcheck_Fail_NotJsonFormat(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        $data = $this->getValidPrivateKeyDataForServer(null, 'super secret key'); // set any value other than json
        $metadataPrivateKey = MetadataPrivateKeyFactory::make(['data' => $data])->withMetadataKey($metadataKey)->serverKey()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $expectedMsg = 'The server metadata private key is not valid.';
        $expectedMsg .= ' ' . sprintf('The metadata private key (id: %s) cleartext data should be in JSON format.', $metadataPrivateKey->get('id'));
        $this->assertStringContainsString($expectedMsg, $this->sut->getFailureMessage());
    }

    /**
     * @backupStaticProperties enabled
     * @return void
     */
    public function testServerPrivateMetadataKeyValidateHealthcheck_Fail_EmptyClearTextData(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        $data = $this->getValidPrivateKeyDataForServer(null, json_encode([])); // set empty data
        $metadataPrivateKey = MetadataPrivateKeyFactory::make(['data' => $data])->withMetadataKey($metadataKey)->serverKey()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $expectedMsg = 'The server metadata private key is not valid.';
        $expectedMsg .= ' ' . sprintf('The metadata private key (id: %s) cleartext data should not be empty.', $metadataPrivateKey->get('id'));
        $this->assertStringContainsString($expectedMsg, $this->sut->getFailureMessage());
    }

    /**
     * @backupStaticProperties enabled
     * @return void
     */
    public function testServerPrivateMetadataKeyValidateHealthcheck_Fail_BadPrivateKeyData(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        $defaultCleartextPrivateKeyData = $this->getValidPrivateKeyCleartext(['armored_key' => 'im private key :P']);
        $data = $this->getValidPrivateKeyDataForServer(null, json_encode($defaultCleartextPrivateKeyData)); // set empty data
        $metadataPrivateKey = MetadataPrivateKeyFactory::make(['data' => $data])->withMetadataKey($metadataKey)->serverKey()->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $expectedMsg = 'The server metadata private key is not valid.';
        $expectedMsg .= ' ' . sprintf('Unable to validate metadata private key (id: %s) cleartext data.', $metadataPrivateKey->get('id'));
        $this->assertStringContainsString($expectedMsg, $this->sut->getFailureMessage());
    }

    /**
     * @backupStaticProperties enabled
     * @return void
     */
    public function testServerPrivateMetadataKeyValidateHealthcheck_Fail_IfAnyOfMultipleKeysContainsInvalidInformation(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $user = UserFactory::make()->active()->user()->persist();
        // key no. 1 with valid private key data, make it latest
        $metadataKey1 = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        MetadataPrivateKeyFactory::make(['created' => DateTime::now()->subDays(1)])->withMetadataKey($metadataKey1)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make(['created' => DateTime::now()->subDays(1)])->withMetadataKey($metadataKey1)->withUser($user)->persist();
        // key no. 2 with invalid private key data, make it in the past
        $metadataKey2 = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        $defaultCleartextPrivateKeyData = $this->getValidPrivateKeyCleartext(['armored_key' => 'im private key :P']);
        $data = $this->getValidPrivateKeyDataForServer(null, json_encode($defaultCleartextPrivateKeyData)); // set wrong armored_key value
        $metadataPrivateKey = MetadataPrivateKeyFactory::make(['data' => $data, 'created' => DateTime::now()->subDays(11)])->withMetadataKey($metadataKey2)->serverKey()->persist();
        MetadataPrivateKeyFactory::make(['created' => DateTime::now()->subDays(10)])->withMetadataKey($metadataKey2)->withUser($user)->persist();

        $this->sut->check();

        $this->assertFalse($this->sut->isPassed());
        $expectedMsg = 'The server metadata private key is not valid.';
        $expectedMsg .= ' ' . sprintf('Unable to validate metadata private key (id: %s) cleartext data.', $metadataPrivateKey->get('id'));
        $this->assertStringContainsString($expectedMsg, $this->sut->getFailureMessage());
    }
}
