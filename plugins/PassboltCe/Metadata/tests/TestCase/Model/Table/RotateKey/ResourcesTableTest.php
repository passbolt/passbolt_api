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
namespace Passbolt\Metadata\Test\TestCase\Model\Table\RotateKey;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

class ResourcesTableTest extends AppTestCaseV5
{
    use FormatValidationTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->Resources);
        parent::tearDown();
    }

    public function testResourcesTable_FindMetadataRotateKeyIndex_EmptyData(): void
    {
        $result = $this->Resources->findMetadataRotateKeyIndex();
        $this->assertInstanceOf(Query::class, $result);
        $this->assertEmpty($result->toArray());
    }

    public function testResourcesTable_FindMetadataRotateKeyIndex_ResourceDeletedNotReturned(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->active()
            ->persist();
        // create expired metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->deleted()->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $user, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $result = $this->Resources->findMetadataRotateKeyIndex();
        $this->assertEmpty($result->toArray());
    }

    public function testResourcesTable_FindMetadataRotateKeyIndex_MetadataKeyTypeUserKeyNotReturned(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->v5Fields(false, [
            'metadata_key_id' => $user->gpgkey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $user, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $result = $this->Resources->findMetadataRotateKeyIndex();
        $this->assertEmpty($result->toArray());
    }

    public function testResourcesTable_FindMetadataRotateKeyIndex_ActiveMetadataResourcesNotReturned(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->active()
            ->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->v5Fields(true, [
            'metadata_key_id' => $metadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $user, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $result = $this->Resources->findMetadataRotateKeyIndex();
        $this->assertEmpty($result->toArray());
    }

    public function testResourcesTable_FindMetadataRotateKeyIndex_V4ResourcesNotReturned(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        $expiredResource = ResourceFactory::make()->withPermissionsFor([$user])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $user, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $result = $this->Resources->findMetadataRotateKeyIndex()->toArray();
        $this->assertCount(1, $result);
        $this->assertSame($expiredResource->get('id'), $result[0]['id']);
    }
}
