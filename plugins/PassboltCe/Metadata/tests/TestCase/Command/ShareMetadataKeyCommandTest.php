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
namespace Passbolt\Metadata\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateResourcesTestTrait;

/**
 * @covers \Passbolt\Metadata\Command\ShareMetadataKeyCommand
 */
class ShareMetadataKeyCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use MigrateResourcesTestTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testShareMetadataKeyCommand_Help()
    {
        $this->exec('passbolt metadata share_metadata_key -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Share metadata keys with users who are missing them.');
        $this->assertOutputContains('cake passbolt metadata share_metadata_key');
    }

    public function testShareMetadataKeyCommand_Success_EmptyKey(): void
    {
        $this->exec('passbolt metadata share_metadata_key');
        $this->assertExitSuccess();
        $this->assertOutputContains('Nothing to share. No metadata key set. Create a metadata key first');
    }

    public function testShareMetadataKeyCommand_Success_NoUsers(): void
    {
        MetadataKeysSettingsFactory::make()->persist();
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $this->exec('passbolt metadata share_metadata_key');
        $this->assertExitSuccess();
        $this->assertOutputContains('No users found');
    }

    public function testShareMetadataKeyCommand_Success_UserFriendlyMode(): void
    {
        MetadataKeysSettingsFactory::make()->persist();
        // metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->persist();
        $deletedMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->deleted()->persist();
        // users
        $activeUser = UserFactory::make()->user()->active()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $activeAdmin */
        $activeAdmin = UserFactory::make()->admin()->active()->withValidGpgKey()->persist();
        $inactiveUser = UserFactory::make()->user()->inactive()->persist();
        $disabledUser = UserFactory::make()->user()->disabled()->withValidGpgKey()->persist();
        $deletedUser = UserFactory::make(['deleted' => true])->user()->withValidGpgKey()->persist();
        // create active metadata key only for active admin
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($activeAdmin->get('gpgkey'))->persist();
        // create expired metadata key for all users except suspended user
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($activeUser->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($activeAdmin->get('gpgkey'))->persist();
        // $deletedMetadataKey is not shared with any user

        $this->exec('passbolt metadata share_metadata_key');

        $this->assertExitSuccess();
        // assert 2 keys shared with $activeUser
        $result = MetadataPrivateKeyFactory::find()->where(['user_id' => $activeUser->get('id')])->toArray();
        $this->assertCount(2, $result);
        // assert 2 keys shared with $activeAdmin
        $result = MetadataPrivateKeyFactory::find()->where(['user_id' => $activeAdmin->get('id')])->toArray();
        $this->assertCount(2, $result);
        // assert no key shared with $inactiveUser
        $result = MetadataPrivateKeyFactory::find()->where(['user_id' => $inactiveUser->get('id')])->toArray();
        $this->assertCount(0, $result);
        // assert 2 keys shared with $disabledUser
        $result = MetadataPrivateKeyFactory::find()->where(['user_id' => $disabledUser->get('id')])->toArray();
        $this->assertCount(2, $result);
        // assert no keys shared with $deletedUser
        $result = MetadataPrivateKeyFactory::find()->where(['user_id' => $deletedUser->get('id')])->toArray();
        $this->assertCount(0, $result);
        // assert no user key entry for $deletedMetadataKey
        $result = MetadataPrivateKeyFactory::find()->where(['metadata_key_id' => $deletedMetadataKey->id, 'user_id IS NOT NULL'])->toArray();
        $this->assertCount(0, $result);

        $metadataPrivateKeysInserted = MetadataPrivateKeyFactory::find()
            ->where(['created >=' => \Cake\I18n\Date::today()])
            ->all();
        $this->assertSame(3, $metadataPrivateKeysInserted->count());
        foreach ($metadataPrivateKeysInserted as $key) {
            $this->assertSame($key->get('created_by'), $activeAdmin->id);
            $this->assertSame($key->get('modified_by'), $activeAdmin->id);
        }
    }

    public function testShareMetadataKeyCommand_Success_ZeroKnowledgeMode(): void
    {
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();
        // users
        UserFactory::make()->user()->active()->withValidGpgKey()->persist();
        $activeAdmin = UserFactory::make()->admin()->active()->withValidGpgKey()->persist();
        // metadata key
        MetadataKeyFactory::make()->withUserPrivateKey($activeAdmin->get('gpgkey'))->persist();

        $this->exec('passbolt metadata share_metadata_key');

        $this->assertExitError();
        $this->assertOutputContains('Cannot share metadata key when in the zero knowledge key share mode');
    }

    public function testShareMetadataKeyCommand_Error_UnableShareWithOneOrMoreUsers(): void
    {
        MetadataKeysSettingsFactory::make()->persist();
        // metadata keys
        $filename = FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg';
        $armoredMessage = file_get_contents($filename);
        $privateKey = MetadataPrivateKeyFactory::make()->patchData(['data' => $armoredMessage, 'user_id' => null]);
        $metadataKey = MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();
        // users
        $activeUser = UserFactory::make()->user()->active()->withValidGpgKey()->persist();
        $activeAdmin = UserFactory::make()->admin()->active()->persist(); // admin doesn't have gpgkeys but is active (unlikely scenario)

        $this->exec('passbolt metadata share_metadata_key');

        $this->assertExitError();
        $this->assertOutputContains("The metadata key {$metadataKey->get('id')} could not be shared with user {$activeUser->get('username')}");
        $this->assertOutputContains("The metadata key {$metadataKey->get('id')} could not be shared with user {$activeAdmin->get('username')}");
    }
}
