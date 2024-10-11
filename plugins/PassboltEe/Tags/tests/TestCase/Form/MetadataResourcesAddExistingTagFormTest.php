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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Form\MetadataResourcesAddExistingTagForm;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Form\MetadataResourcesAddExistingTagForm
 */
class MetadataResourcesAddExistingTagFormTest extends TestCase
{
    use UserAccessControlTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataResourcesAddExistingTagForm|null $form
     */
    protected ?MetadataResourcesAddExistingTagForm $form = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->form = new MetadataResourcesAddExistingTagForm($this->mockAdminAccessControl());
    }

    public function tearDown(): void
    {
        OpenPGPBackendFactory::reset();
        unset($this->form);
        parent::tearDown();
    }

    public function testMetadataResourcesAddExistingTagForm_Success_ExistingSharedTagId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'test-tag']);
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // Create a tag
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $tag = TagFactory::make()
            ->isSharedFor($resource)
            ->v5Fields([
                'metadata' => $metadata,
                'metadata_key_id' => $metadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'is_shared' => true,
            ], true)
            ->persist();

        $uac = $this->makeUac($user);
        $form = new MetadataResourcesAddExistingTagForm($uac);
        $result = $form->execute(['id' => $tag->get('id')]);

        $this->assertTrue($result);
    }

    public function testMetadataResourcesAddExistingTagForm_Success_ExistingPersonalTagId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'personal-tag']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        // Create a tag
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $tag = TagFactory::make()
            ->isSharedFor($resource)
            ->v5Fields([
                'metadata' => $metadata,
                'metadata_key_id' => $user->gpgkey->id,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'is_shared' => false,
            ])
            ->persist();

        $uac = $this->makeUac($user);
        $form = new MetadataResourcesAddExistingTagForm($uac);
        $result = $form->execute(['id' => $tag->get('id')]);

        $this->assertTrue($result);
    }

    public function testMetadataResourcesAddExistingTagForm_Error_Required(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertArrayHasKey('id', $errors);
        $this->assertArrayHasKey('_required', $errors['id']);
    }

    public function testMetadataResourcesAddExistingTagForm_Error_InvalidId(): void
    {
        $result = $this->form->execute(['id' => 'foo-bar']);
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('uuid', $errors['id']);
    }

    public function testMetadataResourcesAddExistingTagForm_Error_IdDoesNotExist(): void
    {
        $result = $this->form->execute(['id' => UuidFactory::uuid()]);
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('tagExists', $errors['id']);
    }

    public function testMetadataResourcesAddExistingTagForm_Error_IdDoesNotBelongToCurrentUser(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $tag = TagFactory::make(['slug' => 'test'])->isPersonalFor($resource, $user)->persist();

        $result = $this->form->execute(['id' => $tag->get('id')]);

        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('tagExists', $errors['id']);
    }
}
