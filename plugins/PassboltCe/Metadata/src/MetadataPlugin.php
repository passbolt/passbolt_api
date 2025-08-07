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
namespace Passbolt\Metadata;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Passbolt\Metadata\Command\GenerateDummyMetadataKeyCommand;
use Passbolt\Metadata\Command\InsertDummyDataCommand;
use Passbolt\Metadata\Command\MigrateAllItemsCommand;
use Passbolt\Metadata\Command\MigrateFoldersCommand;
use Passbolt\Metadata\Command\MigrateResourcesCommand;
use Passbolt\Metadata\Command\ShareMetadataKeyCommand;
use Passbolt\Metadata\Command\UpdateMetadataTypesSettingsCommand;
use Passbolt\Metadata\Event\AddHasManyMetadataPrivateKeysToUsersListener;
use Passbolt\Metadata\Event\MetadataFolderUpdateListener;
use Passbolt\Metadata\Event\MetadataResourceIndexListener;
use Passbolt\Metadata\Event\MetadataResourceUpdateListener;
use Passbolt\Metadata\Event\MetadataUserDeleteSuccessListener;
use Passbolt\Metadata\Event\MissingMetadataKeyIdsContainListener;
use Passbolt\Metadata\Event\SetupCompleteListener;
use Passbolt\Metadata\Notification\Email\Redactor\MetadataEmailRedactorPool;
use Passbolt\Metadata\Service\Healthcheck\NoActiveMetadataKeyHealthcheck;
use Passbolt\Metadata\Service\Healthcheck\ServeMissingAccessToMetadataKeyHealthcheck;
use Passbolt\Metadata\Service\Healthcheck\ServerCanDecryptMetadataPrivateKeyHealthcheck;
use Passbolt\Metadata\Service\Healthcheck\ServerPrivateMetadataKeyValidateHealthcheck;
use Passbolt\Metadata\Service\Migration\MigrateAllV4FoldersToV5Service;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ResourcesToV5Service;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ToV5ServiceCollector;

class MetadataPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        // The configuration isInBeta is written here, as it cannot be overwritten by users.
        Configure::write('passbolt.plugins.metadata.isInBeta', false);
        $this->attachListeners(EventManager::instance());
        // Add migrator services
        MigrateAllV4ToV5ServiceCollector::add([
            MigrateAllV4ResourcesToV5Service::class,
            MigrateAllV4FoldersToV5Service::class,
        ]);
    }

    /**
     * Attach the Locale related event listeners.
     *
     * @param \Cake\Event\EventManager $eventManager EventManager
     * @return void
     */
    public function attachListeners(EventManager $eventManager): void
    {
        $eventManager
            ->on(new MetadataEmailRedactorPool())
            ->on(new SetupCompleteListener())
            ->on(new MetadataUserDeleteSuccessListener())
            ->on(new MetadataResourceIndexListener())
            ->on(new MetadataResourceUpdateListener())
            ->on(new MetadataFolderUpdateListener())
            ->on(new AddHasManyMetadataPrivateKeysToUsersListener())
            ->on(new MissingMetadataKeyIdsContainListener());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(ServerCanDecryptMetadataPrivateKeyHealthcheck::class);
        $container->add(NoActiveMetadataKeyHealthcheck::class);
        $container->add(ServeMissingAccessToMetadataKeyHealthcheck::class);
        $container->add(ServerPrivateMetadataKeyValidateHealthcheck::class);

        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [ServerCanDecryptMetadataPrivateKeyHealthcheck::class])
            ->addMethodCall('addService', [NoActiveMetadataKeyHealthcheck::class])
            ->addMethodCall('addService', [ServeMissingAccessToMetadataKeyHealthcheck::class])
            ->addMethodCall('addService', [ServerPrivateMetadataKeyValidateHealthcheck::class]);
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Alias commands
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $commands->add('passbolt metadata generate_dummy_metadata_key', GenerateDummyMetadataKeyCommand::class);
            $commands->add('passbolt metadata insert_dummy_data', InsertDummyDataCommand::class);
            $commands->add(
                'passbolt metadata update_metadata_types_settings',
                UpdateMetadataTypesSettingsCommand::class
            );
            $commands->add('passbolt metadata share_metadata_key', ShareMetadataKeyCommand::class);
            // Migration commands
            $commands->add('passbolt metadata migrate_resources', MigrateResourcesCommand::class);
            $commands->add('passbolt metadata migrate_folders', MigrateFoldersCommand::class);
            $commands->add('passbolt metadata migrate_all_items', MigrateAllItemsCommand::class);
        }

        return $commands;
    }
}
