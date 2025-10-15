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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Form\RotateKey;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use Passbolt\Metadata\Form\RotateKey\MetadataBatchRotateKeyForm;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Form\RotateKey\MetadataBatchRotateKeyForm
 */
class MetadataBatchUpdateFormTest extends TestCase
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataBatchRotateKeyForm $form
     */
    protected MetadataBatchRotateKeyForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataBatchRotateKeyForm();
    }

    public function tearDown(): void
    {
        unset($this->form);
        parent::tearDown();
    }

    public function testMetadataBatchUpdateForm_Success(): void
    {
        $defaultData = $this->getDefaultData();
        unset($defaultData['metadata_key']['expired']);
        $result = $this->form->execute($defaultData);
        $this->assertTrue($result);
    }

    public function testMetadataBatchUpdateForm_Error_Empty(): void
    {
        $result = $this->form->execute([]);
        $errors = $this->form->getErrors();
        $this->assertFalse($result);
        $requiredFields = ['id', 'metadata_key_id', 'metadata_key_type', 'metadata', 'modified', 'modified_by'];
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey('_empty', $errors[$field]);
        }
    }

    public function testMetadataBatchUpdateForm_Error_InvalidUuid(): void
    {
        $data = array_merge($this->getDefaultData(), [
            'id' => '🔥',
            'metadata_key_id' => 'foo-bar',
            'modified_by' => [],
        ]);
        $result = $this->form->execute($data);
        $errors = $this->form->getErrors();
        $this->assertFalse($result);
        $errorFields = ['id', 'metadata_key_id', 'modified_by'];
        foreach ($errorFields as $field) {
            $this->assertArrayHasKey('uuid', $errors[$field]);
        }
    }

    public function testMetadataBatchUpdateForm_Error_MetadataKeyTypeShouldBeSharedKey(): void
    {
        $data = array_merge($this->getDefaultData(), [
            'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
        ]);
        $result = $this->form->execute($data);
        $errors = $this->form->getErrors();
        $this->assertFalse($result);
        $this->assertArrayHasKey('is_not_shared_key', $errors['metadata_key_type']);
    }

    public function testMetadataBatchUpdateForm_Error_MetadataKeyIdDeleted(): void
    {
        $metadataKey = MetadataKeyFactory::make()->deleted()->persist();
        $data = array_merge($this->getDefaultData(), [
            'metadata_key_id' => $metadataKey->get('id'),
            'metadata_key' => $metadataKey->toArray(),
        ]);
        $result = $this->form->execute($data);
        $errors = $this->form->getErrors();
        $this->assertFalse($result);
        $this->assertArrayHasKey('metadata_key_deleted', $errors['metadata_key']['deleted']);
    }

    public static function metadataRotateKeyResourcesFormModifiedInvalidDateTimeFormat(): array
    {
        return [
            ['🔥'],
            ['20140619'],
            ['2014-05-19'],
        ];
    }

    /**
     * @dataProvider metadataRotateKeyResourcesFormModifiedInvalidDateTimeFormat
     */
    public function testMetadataBatchUpdateForm_Error_Modified_InvalidDateTimeFormat(string $datetime): void
    {
        $data = array_merge($this->getDefaultData(), ['modified' => $datetime]);
        $result = $this->form->execute($data);
        $errors = $this->form->getErrors();
        $this->assertFalse($result);
        $this->assertArrayHasKey('dateTime', $errors['modified']);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function getDefaultData(): array
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$admin])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        return [
            'id' => $resource->get('id'),
            'metadata_key_id' => $activeMetadataKey->get('id'),
            'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
            'modified' => $resource->get('modified'),
            'modified_by' => $resource->get('modified_by'),
            'metadata_key' => $activeMetadataKey->toArray(),
        ];
    }
}
