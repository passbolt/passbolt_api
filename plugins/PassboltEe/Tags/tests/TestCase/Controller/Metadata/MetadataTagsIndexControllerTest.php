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
namespace Passbolt\Tags\Test\TestCase\Controller\Metadata;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Controller\Tags\TagsIndexController
 */
class MetadataTagsIndexControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataTagsIndexController_Success()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $user2 = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user, $user2])->persist();
        TagFactory::make(['slug' => 'test1'])->isPersonalFor($resource, $user)->persist();
        TagFactory::make(['slug' => '#my-fav'])->isSharedFor($resource)->persist();
        TagFactory::make(['slug' => 'marketing'])->isPersonalFor($resource, $user2)->persist();
        // v5 tag
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'test2']);
        $metadataTest2 = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'test3']);
        $metadataTest3 = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $v5TagTest2 = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadataTest2, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        $v5TagTest3 = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadataTest3, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();

        $this->logInAs($user);
        $this->getJson('/tags.json?api-version=v2');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(4, $response);
        foreach ($response as $item) {
            if (in_array($item['id'], [$v5TagTest2->get('id'), $v5TagTest3->get('id')])) {
                $this->assertArrayNotHasKey('slug', $item);
                $this->assertArrayHasKey('id', $item);
                $this->assertArrayHasKey('metadata', $item);
                $this->assertArrayHasKey('metadata_key_id', $item);
                $this->assertArrayHasKey('metadata_key_type', $item);
            } else {
                $this->assertArrayNotHasKey('metadata', $item);
                $this->assertArrayNotHasKey('metadata_key_id', $item);
                $this->assertArrayNotHasKey('metadata_key_type', $item);
                $this->assertArrayHasKey('id', $item);
                $this->assertArrayHasKey('slug', $item);
            }

            // is shared should be present in both case
            $this->assertArrayHasKey('is_shared', $item);
        }
    }
}
