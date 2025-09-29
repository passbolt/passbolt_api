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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Form;

use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventDispatcherTrait;
use Cake\Validation\Validator;
use Passbolt\Metadata\Form\MetadataKeysSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ToV5ServiceCollector;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Form\MetadataKeysSettingsForm
 */
class MetadataKeysSettingsFormTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;
    use FormatValidationTrait;
    use EventDispatcherTrait;

    /**
     * @var MetadataKeysSettingsForm $form
     */
    protected MetadataKeysSettingsForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataKeysSettingsForm();
    }

    public function tearDown(): void
    {
        unset($this->form);
        MigrateAllV4ToV5ServiceCollector::clear();
        parent::tearDown();
    }

    public static function getDefaultData($overrideData = [], $metadataPrivateKeyData = null): array
    {
        $data = array_merge(MetadataKeysSettingsFactory::getDefaultData(), $overrideData);

        if (!is_null($metadataPrivateKeyData)) {
            $data['metadata_private_keys'] = $metadataPrivateKeyData;
        }

        return $data;
    }

    public function testMetadataKeysSettingsForm_Success_UserFriendlyMode(): void
    {
        $data = $this->getDefaultData(
            [MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false],
            [[
                'metadata_key_id' => UuidFactory::uuid(),
                'user_id' => null,
                'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
            ]]
        );
        $this->assertTrue($this->form->execute($data));
    }

    public function testMetadataKeysSettingsForm_Success_ZeroKnowledge(): void
    {
        $data = $this->getDefaultData([MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true]);
        $this->assertTrue($this->form->execute($data));
    }

    public function testMetadataKeysSettingsForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        foreach (MetadataKeysSettingsDto::PROPS as $prop) {
            $this->assertTrue(isset($errors[$prop]['_empty']));
        }
    }

    public function testMetadataKeysSettingsForm_Error_NotBool(): void
    {
        $data = self::getDefaultData([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => 'test',
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => 'test',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS]['boolean']));
        $this->assertTrue(isset($errors[MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE]['boolean']));
    }

    /**
     * @return void
     */
    public function testMetadataKeysSettingsForm_Error_MetadataPrivateKeys_Empty(): void
    {
        $data = self::getDefaultData([], []);
        $form = new MetadataKeysSettingsForm();
        $result = $form->execute($data, ['validate' => 'withMetadataPrivateKeys']);
        $errors = $form->getErrors();
        $this->assertFalse($result);
        $this->assertArrayHasKey('_empty', $errors['metadata_private_keys']);
    }

    public static function metadataPrivateKeysFieldsRequired(): array
    {
        return [
            [[[]]],
            [[[
                'foo' => 'bar',
                'baz' => 'jad',
            ]]],
        ];
    }

    /**
     * @dataProvider metadataPrivateKeysFieldsRequired
     * @return void
     */
    public function testMetadataKeysSettingsForm_Error_MetadataPrivateKeys_FieldsRequired(array $privateKeyData): void
    {
        $data = self::getDefaultData([], $privateKeyData);
        $form = new MetadataKeysSettingsForm();
        $result = $form->execute($data, ['validate' => 'withMetadataPrivateKeys']);
        $errors = $form->getErrors();
        $this->assertFalse($result);
        $requiredFields = ['metadata_key_id', 'user_id', 'data'];
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey('_required', $errors['metadata_private_keys'][0][$field]);
        }
    }

    public function testMetadataKeysSettingsForm_Error_MetadataPrivateKeys_InvalidIds(): void
    {
        $data = self::getDefaultData([], [[
            'metadata_key_id' => 'str',
            'user_id' => UuidFactory::uuid(),
            'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
        ]]);

        $form = new MetadataKeysSettingsForm();
        $result = $form->execute($data, ['validate' => 'withMetadataPrivateKeys']);
        $errors = $form->getErrors();

        $this->assertFalse($result);
        $this->assertArrayHasKey('metadata_private_keys', $errors);
        $this->assertArrayHasKey('uuid', $errors['metadata_private_keys'][0]['metadata_key_id']);
        $this->assertArrayHasKey('onlyNullAllowed', $errors['metadata_private_keys'][0]['user_id']);
    }

    public function testMetadataKeysSettingsForm_Error_MetadataPrivateKeys_Data(): void
    {
        $data = self::getDefaultData([], [[
            'metadata_key_id' => UuidFactory::uuid(),
            'user_id' => null,
            'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
        ]]);

        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmptyString' => self::getNotEmptyTestCases(),
            'ascii' => self::getAsciiTestCases(),
        ];

        $form = new MetadataKeysSettingsForm();
        $validator = new Validator();
        $withMetadataPrivateKeysValidator = $form->validationWithMetadataPrivateKeys($validator);
        $form->setValidator('default', $withMetadataPrivateKeysValidator);
        $this->assertFormFieldFormatValidation(
            $form,
            'metadata_private_keys.0.data',
            $data,
            $testCases
        );
    }
}
