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
 * @since         4.7.0
 */

namespace App\ServiceProvider;

use App\Service\Healthcheck\Application\EmailNotificationEnabledApplicationHealthcheck;
use App\Service\Healthcheck\Application\HostAvailabilityCheckEnabledApplicationHealthcheck;
use App\Service\Healthcheck\Application\JsProdApplicationHealthcheck;
use App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck;
use App\Service\Healthcheck\Application\RobotsIndexDisabledApplicationHealthcheck;
use App\Service\Healthcheck\Application\SeleniumDisabledApplicationHealthcheck;
use App\Service\Healthcheck\Application\SelfRegistrationPluginEnabledApplicationHealthcheck;
use App\Service\Healthcheck\Application\SelfRegistrationProviderApplicationHealthcheck;
use App\Service\Healthcheck\Application\SelfRegistrationPublicRemovedApplicationHealthcheck;
use App\Service\Healthcheck\Application\SessionProviderApplicationHealthcheck;
use App\Service\Healthcheck\Application\SslForceApplicationHealthcheck;
use App\Service\Healthcheck\Application\SslFullBaseUrlApplicationHealthcheck;
use App\Service\Healthcheck\ConfigFiles\AppConfigFileHealthcheck;
use App\Service\Healthcheck\ConfigFiles\PassboltConfigFileHealthcheck;
use App\Service\Healthcheck\Core\CacheConfigCoreHealthcheck;
use App\Service\Healthcheck\Core\DebugDisabledCoreHealthcheck;
use App\Service\Healthcheck\Core\ExcludedHealthchecksCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlReachableCoreHealthcheck;
use App\Service\Healthcheck\Core\SaltDefinedCoreHealthcheck;
use App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck;
use App\Service\Healthcheck\Database\DefaultContentDatabaseHealthcheck;
use App\Service\Healthcheck\Database\MariadbMysqlVersionDeprecateHealthcheck;
use App\Service\Healthcheck\Database\ModelCacheDatabaseHealthcheck;
use App\Service\Healthcheck\Database\SchemaUpToDateApplicationHealthcheck;
use App\Service\Healthcheck\Database\TablesCountDatabaseHealthcheck;
use App\Service\Healthcheck\Environment\DistributionEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\GpgEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\ImageEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\IntlEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\LogFolderWritableHealthcheck;
use App\Service\Healthcheck\Environment\MbstringEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\OperatingSystemHealthcheck;
use App\Service\Healthcheck\Environment\PcreEnvironmentHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\TimeSyncHealthcheck;
use App\Service\Healthcheck\Environment\TmpFolderWritableHealthcheck;
use App\Service\Healthcheck\Gpg\CanDecryptGpgHealthcheck;
use App\Service\Healthcheck\Gpg\CanDecryptVerifyGpgHealthcheck;
use App\Service\Healthcheck\Gpg\CanEncryptGpgHealthcheck;
use App\Service\Healthcheck\Gpg\CanEncryptSignGpgHealthcheck;
use App\Service\Healthcheck\Gpg\CanSignGpgHealthcheck;
use App\Service\Healthcheck\Gpg\CanVerifyGpgHealthcheck;
use App\Service\Healthcheck\Gpg\FingerprintMatchGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GopengpgPrivateKeyFormatGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GopengpgPublicKeyFormatGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\KeyNotDefaultGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PrivateKeyReadableAndParsableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PublicKeyEmailGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PublicKeyInKeyringGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PublicKeyReadableAndParsableGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\Jwt\PluginEnabledJwtHealthcheck;
use App\Service\Healthcheck\SmtpSettings\PluginEnabledSmtpSettingsHealthcheck;
use App\Service\Healthcheck\Ssl\HostValidSslHealthcheck;
use App\Service\Healthcheck\Ssl\IsRequestHttpsSslHealthcheck;
use App\Service\Healthcheck\Ssl\NotSelfSignedSslHealthcheck;
use App\Service\Healthcheck\Ssl\PeerValidSslHealthcheck;
use App\Service\Network\SocketService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\Client;
use Cake\Http\ServerRequest;
use Passbolt\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService;

class HealthcheckServiceProvider extends ServiceProvider
{
    protected array $provides = [
        DistributionEnvironmentHealthcheck::class,
        HealthcheckServiceCollector::class,
        PhpVersionHealthcheck::class,
        NextMinPhpVersionHealthcheck::class,
        OperatingSystemHealthcheck::class,
        GpgEnvironmentHealthcheck::class,
        PcreEnvironmentHealthcheck::class,
        MbstringEnvironmentHealthcheck::class,
        IntlEnvironmentHealthcheck::class,
        ImageEnvironmentHealthcheck::class,
        TmpFolderWritableHealthcheck::class,
        LogFolderWritableHealthcheck::class,
        AppConfigFileHealthcheck::class,
        PassboltConfigFileHealthcheck::class,
        CacheConfigCoreHealthcheck::class,
        DebugDisabledCoreHealthcheck::class,
        SaltDefinedCoreHealthcheck::class,
        FullBaseUrlCoreHealthcheck::class,
        ValidFullBaseUrlCoreHealthcheck::class,
        FullBaseUrlReachableCoreHealthcheck::class,
        LatestVersionApplicationHealthcheck::class,
        SslForceApplicationHealthcheck::class,
        SslFullBaseUrlApplicationHealthcheck::class,
        SeleniumDisabledApplicationHealthcheck::class,
        RobotsIndexDisabledApplicationHealthcheck::class,
        SelfRegistrationHealthcheckService::class,
        SelfRegistrationPluginEnabledApplicationHealthcheck::class,
        SelfRegistrationProviderApplicationHealthcheck::class,
        SelfRegistrationPublicRemovedApplicationHealthcheck::class,
        HostAvailabilityCheckEnabledApplicationHealthcheck::class,
        JsProdApplicationHealthcheck::class,
        EmailNotificationEnabledApplicationHealthcheck::class,
        SessionProviderApplicationHealthcheck::class,
        ConnectDatabaseHealthcheck::class,
        ModelCacheDatabaseHealthcheck::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // Register service itself to container
        // Environment health checks
        $container->add(DistributionEnvironmentHealthcheck::class);
        $container->add(PhpVersionHealthcheck::class);
        $container->add(NextMinPhpVersionHealthcheck::class);
        $container->add(OperatingSystemHealthcheck::class);
        $container->add(GpgEnvironmentHealthcheck::class);
        $container->add(PcreEnvironmentHealthcheck::class);
        $container->add(MbstringEnvironmentHealthcheck::class);
        $container->add(IntlEnvironmentHealthcheck::class);
        $container->add(ImageEnvironmentHealthcheck::class);
        $container->add(TmpFolderWritableHealthcheck::class);
        $container->add(LogFolderWritableHealthcheck::class);
        $container->add(TimeSyncHealthcheck::class);
        // Config files health checks
        $container->add(AppConfigFileHealthcheck::class);
        $container->add(PassboltConfigFileHealthcheck::class);
        // Core health checks
        $container->add(CacheConfigCoreHealthcheck::class);
        $container->add(DebugDisabledCoreHealthcheck::class);
        $container->add(ExcludedHealthchecksCoreHealthcheck::class)
            ->addArgument(fn() => $container->get(HealthcheckServiceCollector::class));
        $container->add(SaltDefinedCoreHealthcheck::class);
        $container->add(FullBaseUrlCoreHealthcheck::class);
        $container->add(ValidFullBaseUrlCoreHealthcheck::class);
        $container->add('fullBaseUrlReachableClient', Client::class);
        $container->addShared(FullBaseUrlReachableCoreHealthcheck::class)->addArgument('fullBaseUrlReachableClient');
        // SSL health checks
        $container->add('sslHealthcheckClient', Client::class);
        $container->add(PeerValidSslHealthcheck::class)
            ->addArguments([FullBaseUrlReachableCoreHealthcheck::class, 'sslHealthcheckClient']);
        $container->add(HostValidSslHealthcheck::class)
            ->addArguments([FullBaseUrlReachableCoreHealthcheck::class, 'sslHealthcheckClient']);
        $container->add(NotSelfSignedSslHealthcheck::class)
            ->addArguments([FullBaseUrlReachableCoreHealthcheck::class, 'sslHealthcheckClient']);
        // Smtp Settings default healthcheck
        $container->add(PluginEnabledSmtpSettingsHealthcheck::class);
        // JWT default healthcheck
        $container->add(PluginEnabledJwtHealthcheck::class);
        // Gpg health checks
        $container->add(PhpGpgModuleInstalledGpgHealthcheck::class);
        $container->add(HomeVariableDefinedGpgHealthcheck::class);
        $container->add(HomeVariableWritableGpgHealthcheck::class);
        $container->add(KeyNotDefaultGpgHealthcheck::class);
        $container->add(PublicKeyReadableAndParsableGpgHealthcheck::class);
        $container->add(PrivateKeyReadableAndParsableGpgHealthcheck::class);
        $container->add(FingerprintMatchGpgHealthcheck::class);
        $container->addShared(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(PublicKeyEmailGpgHealthcheck::class);
        $container->addShared(CanEncryptGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(CanSignGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(CanEncryptSignGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(CanDecryptGpgHealthcheck::class)
            ->addArgument(CanEncryptGpgHealthcheck::class);
        $container->addShared(CanDecryptVerifyGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(CanVerifyGpgHealthcheck::class)
            ->addArgument(CanDecryptVerifyGpgHealthcheck::class);
        $container->add(GopengpgPublicKeyFormatGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        $container->add(GopengpgPrivateKeyFormatGpgHealthcheck::class)
            ->addArgument(PublicKeyInKeyringGpgHealthcheck::class);
        // Application health checks
        $container->add(SocketService::class);
        $container->add(LatestVersionApplicationHealthcheck::class)
            ->addArgument(Client::class)
            ->addArgument(SocketService::class);
        $container->add(SslForceApplicationHealthcheck::class);
        $container->add(SslFullBaseUrlApplicationHealthcheck::class);
        $container->add(SeleniumDisabledApplicationHealthcheck::class);
        $container->add(RobotsIndexDisabledApplicationHealthcheck::class);
        $container->addShared(SelfRegistrationHealthcheckService::class);
        $container
            ->add(SelfRegistrationPluginEnabledApplicationHealthcheck::class)
            ->addArgument(SelfRegistrationHealthcheckService::class);
        $container
            ->add(SelfRegistrationProviderApplicationHealthcheck::class)
            ->addArgument(SelfRegistrationHealthcheckService::class);
        $container
            ->add(SelfRegistrationPublicRemovedApplicationHealthcheck::class)
            ->addArgument(SelfRegistrationHealthcheckService::class);
        $container->add(HostAvailabilityCheckEnabledApplicationHealthcheck::class);
        $container->add(JsProdApplicationHealthcheck::class);
        $container->add(EmailNotificationEnabledApplicationHealthcheck::class);
        $container->add(SessionProviderApplicationHealthcheck::class);
        // Database health checks
        $container->add(ConnectDatabaseHealthcheck::class);
        $container->add(TablesCountDatabaseHealthcheck::class);
        $container->add(DefaultContentDatabaseHealthcheck::class);
        $container->add(MariadbMysqlVersionDeprecateHealthcheck::class);
        $container->add(SchemaUpToDateApplicationHealthcheck::class);
        $container->add(ModelCacheDatabaseHealthcheck::class);

        // Append core health checks to service collector
        $container->add(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [DistributionEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [PhpVersionHealthcheck::class])
            ->addMethodCall('addService', [NextMinPhpVersionHealthcheck::class])
            ->addMethodCall('addService', [OperatingSystemHealthcheck::class])
            ->addMethodCall('addService', [GpgEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [PcreEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [MbstringEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [IntlEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [ImageEnvironmentHealthcheck::class])
            ->addMethodCall('addService', [TmpFolderWritableHealthcheck::class])
            ->addMethodCall('addService', [LogFolderWritableHealthcheck::class])
            ->addMethodCall('addService', [TimeSyncHealthcheck::class])
            ->addMethodCall('addService', [AppConfigFileHealthcheck::class])
            ->addMethodCall('addService', [PassboltConfigFileHealthcheck::class])
            ->addMethodCall('addService', [CacheConfigCoreHealthcheck::class])
            ->addMethodCall('addService', [DebugDisabledCoreHealthcheck::class])
            ->addMethodCall('addService', [ExcludedHealthchecksCoreHealthcheck::class])
            ->addMethodCall('addService', [SaltDefinedCoreHealthcheck::class])
            ->addMethodCall('addService', [FullBaseUrlCoreHealthcheck::class])
            ->addMethodCall('addService', [ValidFullBaseUrlCoreHealthcheck::class])
            ->addMethodCall('addService', [FullBaseUrlReachableCoreHealthcheck::class])
            ->addMethodCall('addService', [PeerValidSslHealthcheck::class])
            ->addMethodCall('addService', [HostValidSslHealthcheck::class])
            ->addMethodCall('addService', [NotSelfSignedSslHealthcheck::class])
            ->addMethodCall('addService', [PluginEnabledSmtpSettingsHealthcheck::class])
            ->addMethodCall('addService', [PluginEnabledJwtHealthcheck::class])
            ->addMethodCall('addService', [PhpGpgModuleInstalledGpgHealthcheck::class])
            ->addMethodCall('addService', [HomeVariableDefinedGpgHealthcheck::class])
            ->addMethodCall('addService', [HomeVariableWritableGpgHealthcheck::class])
            ->addMethodCall('addService', [KeyNotDefaultGpgHealthcheck::class])
            ->addMethodCall('addService', [PublicKeyReadableAndParsableGpgHealthcheck::class])
            ->addMethodCall('addService', [PrivateKeyReadableAndParsableGpgHealthcheck::class])
            ->addMethodCall('addService', [FingerprintMatchGpgHealthcheck::class])
            ->addMethodCall('addService', [PublicKeyInKeyringGpgHealthcheck::class])
            ->addMethodCall('addService', [PublicKeyEmailGpgHealthcheck::class])
            ->addMethodCall('addService', [CanEncryptGpgHealthcheck::class])
            ->addMethodCall('addService', [CanSignGpgHealthcheck::class])
            ->addMethodCall('addService', [CanEncryptSignGpgHealthcheck::class])
            ->addMethodCall('addService', [CanDecryptGpgHealthcheck::class])
            ->addMethodCall('addService', [CanDecryptVerifyGpgHealthcheck::class])
            ->addMethodCall('addService', [CanVerifyGpgHealthcheck::class])
            ->addMethodCall('addService', [GopengpgPublicKeyFormatGpgHealthcheck::class])
            ->addMethodCall('addService', [GopengpgPrivateKeyFormatGpgHealthcheck::class])
            ->addMethodCall('addService', [LatestVersionApplicationHealthcheck::class])
            ->addMethodCall('addService', [SslForceApplicationHealthcheck::class])
            ->addMethodCall('addService', [SslFullBaseUrlApplicationHealthcheck::class])
            ->addMethodCall('addService', [SeleniumDisabledApplicationHealthcheck::class])
            ->addMethodCall('addService', [RobotsIndexDisabledApplicationHealthcheck::class])
            ->addMethodCall('addService', [SelfRegistrationPluginEnabledApplicationHealthcheck::class])
            ->addMethodCall('addService', [SelfRegistrationProviderApplicationHealthcheck::class])
            ->addMethodCall('addService', [SelfRegistrationPublicRemovedApplicationHealthcheck::class])
            ->addMethodCall('addService', [HostAvailabilityCheckEnabledApplicationHealthcheck::class])
            ->addMethodCall('addService', [JsProdApplicationHealthcheck::class])
            ->addMethodCall('addService', [EmailNotificationEnabledApplicationHealthcheck::class])
            ->addMethodCall('addService', [SessionProviderApplicationHealthcheck::class])
            ->addMethodCall('addService', [ConnectDatabaseHealthcheck::class])
            ->addMethodCall('addService', [TablesCountDatabaseHealthcheck::class])
            ->addMethodCall('addService', [DefaultContentDatabaseHealthcheck::class])
            ->addMethodCall('addService', [MariadbMysqlVersionDeprecateHealthcheck::class])
            ->addMethodCall('addService', [SchemaUpToDateApplicationHealthcheck::class])
            ->addMethodCall('addService', [ModelCacheDatabaseHealthcheck::class]);

        // Required for Healthcheck endpoint
        $container->add(IsRequestHttpsSslHealthcheck::class)->addArgument(ServerRequest::class);
    }
}
