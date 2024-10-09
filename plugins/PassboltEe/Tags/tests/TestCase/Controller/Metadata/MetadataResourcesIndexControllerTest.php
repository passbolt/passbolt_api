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
 * @since         4.9.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller\Metadata;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;

class MetadataResourcesIndexControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(FoldersPlugin::class);
        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataResourcesIndexController_Success_ContainV5Tags()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        // V4 tag
        /** @var \Passbolt\Tags\Model\Entity\Tag $v4Tag */
        $v4Tag = TagFactory::make(['slug' => 'marketing'])->isPersonalFor($resource, $user)->persist();
        // V5 tag
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'favourite']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        /** @var \Passbolt\Tags\Model\Entity\Tag $v5Tag */
        $v5Tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // Persist another tag, that should be ignored
        TagFactory::make()->persist();
        $this->logInAs($user);

        $this->getJson('/resources.json?contain[tag]=1');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $tags = $response[0]['tags'];
        $this->assertCount(2, $tags);
        foreach ($tags as $tag) {
            if ($tag['id'] === $v5Tag->id) {
                // v5 tag
                $this->assertArrayNotHasKey('slug', $tag);
                $this->assertSame($metadata, $tag['metadata']);
                $this->assertSame($user->gpgkey->id, $tag['metadata_key_id']);
                $this->assertSame('user_key', $tag['metadata_key_type']);
                $this->assertFalse($tag['is_shared']);
            } else {
                // v4 tag
                $this->assertArrayNotHasKey('metadata', $tag);
                $this->assertArrayNotHasKey('metadata_key_id', $tag);
                $this->assertArrayNotHasKey('metadata_key_type', $tag);
                $this->assertSame($v4Tag->id, $tag['id']);
                $this->assertSame('marketing', $tag['slug']);
                $this->assertFalse($tag['is_shared']);
            }
        }
    }
}
