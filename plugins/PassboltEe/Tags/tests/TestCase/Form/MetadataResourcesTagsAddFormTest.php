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

namespace Passbolt\Tags\Test\TestCase\Form;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Form\MetadataResourcesTagsAddForm;

/**
 * @covers \Passbolt\Tags\Form\MetadataResourcesTagsAddForm
 */
class MetadataResourcesTagsAddFormTest extends AppTestCaseV5
{
    use UserAccessControlTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataResourcesTagsAddForm|null $form
     */
    protected ?MetadataResourcesTagsAddForm $form = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataResourcesTagsAddForm();
    }

    public function tearDown(): void
    {
        OpenPGPBackendFactory::reset();
        unset($this->form);
        parent::tearDown();
    }

    public function testMetadataResourcesTagsAddForm_Success_SharedTag(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'test-tag']);
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $result = $this->form->execute([
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->get('id'),
            'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
            'is_shared' => true,
        ]);

        $this->assertTrue($result);
    }

    public function testMetadataResourcesTagsAddForm_Success_PersonalTag(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'personal-tag']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());

        $result = $this->form->execute([
            'metadata' => $metadata,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
            'is_shared' => false,
        ]);

        $this->assertTrue($result);
    }

    public function testMetadataResourcesTagsAddForm_Error_Required(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertCount(3, $errors);
        $fields = ['metadata', 'metadata_key_type', 'is_shared'];
        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $errors);
            $this->assertArrayHasKey('_required', $errors[$field]);
        }
    }
}
