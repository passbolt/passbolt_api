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
namespace Passbolt\Metadata\Test\TestCase\Command;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateResourcesTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Command\MigrateResourcesCommand
 */
class MigrateResourcesCommandTest extends AppIntegrationTestCaseV5
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

        $this->useCommandRunner();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMigrateResourcesCommand_Help()
    {
        $this->exec('passbolt metadata migrate_resources -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Migrate V4 resources to V5.');
        $this->assertOutputContains('cake passbolt metadata migrate_resources');
    }

    public function testMigrateResourcesCommand_Success_MultipleResources(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $totpStandalone = ResourceTypeFactory::make()->standaloneTotp()->persist();
        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $v5TotpStandalone */
        $v5TotpStandalone = ResourceTypeFactory::make()->v5StandaloneTotp()->persist();
        // Shared resource.
        /** @var \App\Model\Entity\Resource $resource */
        $sharedResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission(UserFactory::make()->persist())
            ->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeRead()->withAroUser()->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeUpdate()->withAroUser()->persist();
        // Personal resource.
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make([
                'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
            ]))
            ->persist();
        /** @var \App\Model\Entity\Resource $personalResource */
        $personalResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($user)
            ->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->exec('passbolt metadata migrate_resources');

        $this->assertExitSuccess();
        /** @var \App\Model\Entity\Resource[] $updatedResources */
        $updatedResources = ResourceFactory::find()->toArray();
        $this->assertCount(2, $updatedResources);
        foreach ($updatedResources as $updatedResource) {
            if ($updatedResource->id === $personalResource->id) { // personal resource assertions
                $this->assertionsForPersonalResource($updatedResource, $personalResource, $user->gpgkey, [
                    'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
                    'passphrase' => 'ada@passbolt.com',
                ]);
            } else { // shared resource assertions
                $this->assertionsForSharedResource($updatedResource, $sharedResource, $metadataKey);
            }
        }
    }

    public function testMigrateResourcesCommand_Error_PartialFailures(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $totpStandalone = ResourceTypeFactory::make()->standaloneTotp()->persist();
        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $v5TotpStandalone */
        $v5TotpStandalone = ResourceTypeFactory::make()->v5StandaloneTotp()->persist();
        // Shared resource.
        /** @var \App\Model\Entity\Resource $sharedResource */
        $sharedResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission(UserFactory::make()->persist())
            ->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeRead()->withAroUser()->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeUpdate()->withAroUser()->persist();
        // Personal resource.
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make([
                'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
            ]))
            ->persist();
        /** @var \App\Model\Entity\Resource $personalResource */
        $personalResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($user)
            ->persist();
        // we do not create metadata key to get an error for shared resources

        $this->exec('passbolt metadata migrate_resources');

        $this->assertExitError();
        $this->assertOutputContains('<success>1 resources were migrated.</success>');
        $this->assertOutputContains('All resources could not migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('Record not found in table "metadata_keys"');
        // Make sure v5 fields are updated
        $updatedResource = ResourceFactory::get($personalResource->id);
        $this->assertionsForPersonalResource($updatedResource, $personalResource, $user->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
            'passphrase' => 'ada@passbolt.com',
        ]);
        // Make sure v4 fields are present due to failure
        $updatedResource = ResourceFactory::get($sharedResource->id);
        $this->assertNotNull($updatedResource->name);
        $this->assertNotNull($updatedResource->username);
        $this->assertNotNull($updatedResource->uri);
        $this->assertNull($updatedResource->get('metadata'));
        $this->assertNull($updatedResource->get('metadata_key_id'));
        $this->assertNull($updatedResource->get('metadata_key_type'));
    }

    public function testMigrateResourcesCommand_Error_NoResources(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();

        $this->exec('passbolt metadata migrate_resources');

        $this->assertExitError();
        $this->assertOutputContains('All resources could not migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('No resources to migrate.');
    }

    public function testMigrateResourcesCommand_Error_AllowCreationOfV5ResourcesDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist(); // only allow V4 format
        $v4ResourceType = ResourceTypeFactory::make()->passwordAndDescription()->persist();
        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $v5DefaultResourceType */
        ResourceTypeFactory::make()->v5Default()->persist();
        $adaKeyInfo = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
        ];
        $bettyKeyInfo = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key'),
            'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
        ];
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make($adaKeyInfo))
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        ResourceFactory::make()
            ->with('ResourceTypes', $v4ResourceType)
            ->withPermissionsFor([$ada])
            ->withCreator(UserFactory::make()->user()->with('Gpgkeys', GpgkeyFactory::make($bettyKeyInfo)))
            ->persist();

        $this->exec('passbolt metadata migrate_resources');

        $this->assertExitError();
        $this->assertErrorContains('Resource creation/modification with encrypted metadata not allowed');
    }
}
