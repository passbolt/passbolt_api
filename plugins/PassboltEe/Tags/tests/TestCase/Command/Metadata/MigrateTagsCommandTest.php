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
namespace Passbolt\Tags\Test\TestCase\Command\Metadata;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ToV5ServiceCollector;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\Metadata\MigrateTagsTestTrait;

/**
 * @covers \Passbolt\Tags\Command\Metadata\MigrateTagsCommand
 */
class MigrateTagsCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use GpgMetadataKeysTestTrait;
    use MigrateTagsTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(TagsPlugin::class);
        $this->enableFeaturePlugin(MetadataPlugin::class);
        // clear collector state to get proper results
        MigrateAllV4ToV5ServiceCollector::clear();
    }

    public function testMigrateTagsCommand_Help()
    {
        $this->exec('passbolt metadata migrate_tags -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Migrate V4 tags to V5.');
        $this->assertOutputContains('cake passbolt metadata migrate_tags');
    }

    public function testMigrateTagsCommand_Success(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        // Personal tag of Ada
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $personalTag = TagFactory::make(['slug' => 'special'])->isPersonalFor($resource, $ada)->persist();
        // Shared tag
        $sharedTag = TagFactory::make(['slug' => 'marketing'])->isSharedFor($resource)->isShared()->persist();

        $this->exec('passbolt metadata migrate_tags');

        $this->assertExitSuccess();
        $updatedTags = TagFactory::find()->toArray();
        $this->assertCount(2, $updatedTags);
        // assert updated personal tag
        $updatedTag1 = TagFactory::get($personalTag->get('id'));
        $this->assertionsForPersonalTag($updatedTag1, $personalTag, $ada->gpgkey, $this->getAdaNoPassphraseKeyInfo());
        // assert updated shared tag
        $updatedTag2 = TagFactory::get($sharedTag->get('id'));
        $this->assertionsForSharedTag($updatedTag2, $sharedTag, $metadataKey);
    }

    public function testMigrateTagsCommand_Error_PartialFailures(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // Personal tag of Ada
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $personalTag = TagFactory::make(['slug' => 'special'])->isPersonalFor($resource, $ada)->persist();
        // Shared tag
        $sharedTag = TagFactory::make(['slug' => 'marketing'])->isSharedFor($resource)->isShared()->persist();

        $this->exec('passbolt metadata migrate_tags');

        $this->assertExitError();
        $this->assertOutputContains('<success>1 tags were migrated.</success>');
        $this->assertOutputContains('All tags could not be migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('Record not found in table "metadata_keys"');
        // assert values in db
        $tags = TagFactory::find()->toArray();
        $this->assertCount(2, $tags);
        // assert updated personal tag
        $updatedTag1 = TagFactory::get($personalTag->get('id'));
        $this->assertionsForPersonalTag($updatedTag1, $personalTag, $ada->gpgkey, $this->getAdaNoPassphraseKeyInfo());
    }

    public function testMigrateTagsCommand_Error_NoTags(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();

        $this->exec('passbolt metadata migrate_tags');

        $this->assertExitError();
        $this->assertOutputContains('All tags could not be migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('No tags to migrate.');
    }

    public function testMigrateTagsCommand_Error_CreationOfV5FoldersIsDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();

        $this->exec('passbolt metadata migrate_tags');

        $this->assertExitError();
        $this->assertErrorContains('Tag creation/modification with encrypted metadata not allowed.');
        $this->assertErrorContains('To enable, set "allow_creation_of_v5_tags" metadata settings to true via `update_metadata_types_settings` command');
    }
}
