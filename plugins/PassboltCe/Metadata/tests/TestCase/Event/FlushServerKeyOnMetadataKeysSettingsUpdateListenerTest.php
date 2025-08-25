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
 * @since         5.5.0
 */

namespace Passbolt\Metadata\Test\TestCase\Event;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\Event\Event;
use Passbolt\Metadata\Event\FlushServerKeyOnMetadataKeysSettingsUpdateListener;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Service\MetadataKeysSettingsSetService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;

/**
 * @covers \Passbolt\Metadata\Event\FlushServerKeyOnMetadataKeysSettingsUpdateListener
 */
class FlushServerKeyOnMetadataKeysSettingsUpdateListenerTest extends AppTestCaseV5
{
    /**
     * @var \Passbolt\Metadata\Event\FlushServerKeyOnMetadataKeysSettingsUpdateListener
     */
    private FlushServerKeyOnMetadataKeysSettingsUpdateListener $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new FlushServerKeyOnMetadataKeysSettingsUpdateListener();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testFlushServerKeyOnMetadataKeysSettingsUpdateListener_ZeroKnowledge(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withMetadataKey($metadataKey)->persist();
        $metadataKeySettings = json_encode([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
        $metadataKeysSettings = MetadataKeysSettingsFactory::make()->value($metadataKeySettings)->persist();

        $event = new Event(MetadataKeysSettingsSetService::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME);
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ];
        // Mimic update
        $updatedEntity = $metadataKeysSettings->patch(['value' => json_encode($data)]);
        $eventData = [
            'dto' => new MetadataKeysSettingsDto($data),
            'updatedEntity' => $updatedEntity,
            'uac' => $this->mockAdminAccessControl(),
        ];
        $event->setData($eventData);
        $this->sut->flushMetadataServerKey($event);

        $expectedPrivateKeys = MetadataPrivateKeyFactory::find()->all()->toArray();
        $this->assertCount(1, $expectedPrivateKeys);
        // Make sure entry deleted is not the user's
        $this->assertNotNull($expectedPrivateKeys[0]['user_id']);
    }

    public function testFlushServerKeyOnMetadataKeysSettingsUpdateListener_Shared(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withMetadataKey($metadataKey)->persist();
        $metadataKeySettings = json_encode([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
        $metadataKeysSettings = MetadataKeysSettingsFactory::make()->value($metadataKeySettings)->persist();

        $event = new Event(MetadataKeysSettingsSetService::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME);
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ];
        // Mimic update
        $updatedEntity = $metadataKeysSettings->patch(['value' => json_encode($data)]);
        $eventData = [
            'dto' => new MetadataKeysSettingsDto($data),
            'updatedEntity' => $updatedEntity,
            'uac' => $this->mockAdminAccessControl(),
        ];
        $event->setData($eventData);
        $this->sut->flushMetadataServerKey($event);

        $expectedPrivateKeys = MetadataPrivateKeyFactory::find()->all()->toArray();
        $this->assertCount(2, $expectedPrivateKeys);
    }

    public function testFlushServerKeyOnMetadataKeysSettingsUpdateListener_InvalidValuesGracefullyHandled(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withMetadataKey($metadataKey)->persist();
        $metadataKeysSettings = MetadataKeysSettingsFactory::make()->value('foo')->persist();

        $event = new Event(MetadataKeysSettingsSetService::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME);
        // Mimic update
        $updatedEntity = $metadataKeysSettings->patch(['value' => 'foo updated']);
        $eventData = [
            'dto' => new MetadataKeysSettingsDto(),
            'updatedEntity' => $updatedEntity,
            'uac' => $this->mockAdminAccessControl(),
        ];
        $event->setData($eventData);
        $this->sut->flushMetadataServerKey($event);

        $expectedPrivateKeys = MetadataPrivateKeyFactory::find()->all()->toArray();
        $this->assertCount(2, $expectedPrivateKeys);
    }
}
