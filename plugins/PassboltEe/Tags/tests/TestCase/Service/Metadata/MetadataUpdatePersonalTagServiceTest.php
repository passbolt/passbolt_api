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

namespace Passbolt\Tags\Test\TestCase\Service\Metadata;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateFoldersTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateResourcesTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\Tags\UpdatePersonalTagService;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\Metadata\MigrateTagsTestTrait;

/**
 * @covers \Passbolt\Tags\Service\Tags\UpdatePersonalTagService
 */
class MetadataUpdatePersonalTagServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;
    use MigrateFoldersTestTrait;
    use MigrateResourcesTestTrait;
    use MigrateTagsTestTrait;
    use UserAccessControlTrait;

    /**
     * @var UpdatePersonalTagService|null
     */
    private ?UpdatePersonalTagService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new UpdatePersonalTagService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataUpdatePersonalTagService_Error_V5ToV4DowngradeNotAllowed(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'old']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // data to update
        $newData = ['slug' => 'tag updated'];

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('The settings selected by your administrator prevent from downgrading tag');

        $uac = $this->makeUac($user);
        $tagDto = MetadataTagDto::fromArray($newData);
        $this->service->update($uac, $tagDto, $tag);
    }
}
