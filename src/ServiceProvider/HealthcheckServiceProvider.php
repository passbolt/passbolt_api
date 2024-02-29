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
 * @since         4.6.0
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
use App\Service\Healthcheck\Application\SslForceApplicationHealthcheck;
use App\Service\Healthcheck\Application\SslFullBaseUrlApplicationHealthcheck;
use App\Service\Healthcheck\ConfigFiles\AppConfigFileHealthcheck;
use App\Service\Healthcheck\ConfigFiles\PassboltConfigFileHealthcheck;
use App\Service\Healthcheck\Core\CacheCoreHealthcheck;
use App\Service\Healthcheck\Core\DebugDisabledCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlReachableCoreHealthcheck;
use App\Service\Healthcheck\Core\SaltCoreHealthcheck;
use App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck;
use App\Service\Healthcheck\Database\DefaultContentDatabaseHealthcheck;
use App\Service\Healthcheck\Database\SchemaUpToDateDatabaseHealthcheck;
use App\Service\Healthcheck\Database\TablesCountDatabaseHealthcheck;
use App\Service\Healthcheck\Environment\ImageHealthcheck;
use App\Service\Healthcheck\Environment\IntlHealthcheck;
use App\Service\Healthcheck\Environment\LogFolderWritableHealthcheck;
use App\Service\Healthcheck\Environment\MbstringHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PcreHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\TmpFolderWritableHealthcheck;
use App\Service\Healthcheck\Gpg\GpgHomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgKeyNotDefaultGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgPrivateKeyFingerprintMatchGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgPrivateKeyReadableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgPublicKeyEmailGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgPublicKeyInKeyringGpgHealthcheck;
use App\Service\Healthcheck\Gpg\GpgPublicKeyReadableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\Jwt\PluginEnabledJwtHealthcheck;
use App\Service\Healthcheck\SmtpSettings\PluginEnabledSmtpSettingsHealthcheck;
use App\Service\Healthcheck\Ssl\HostValidSslHealthcheck;
use App\Service\Healthcheck\Ssl\NotSelfSignedSslHealthcheck;
use App\Service\Healthcheck\Ssl\PeerValidSslHealthcheck;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\Client;
use Passbolt\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService;

class HealthcheckServiceProvider extends ServiceProvider
{
    protected $provides = [
        HealthcheckServiceCollector::class,
        PhpVersionHealthcheck::class,
        NextMinPhpVersionHealthcheck::class,
        PcreHealthcheck::class,
        MbstringHealthcheck::class,
        IntlHealthcheck::class,
        ImageHealthcheck::class,
        TmpFolderWritableHealthcheck::class,
        LogFolderWritableHealthcheck::class,
        AppConfigFileHealthcheck::class,
        PassboltConfigFileHealthcheck::class,
        CacheCoreHealthcheck::class,
        DebugDisabledCoreHealthcheck::class,
        SaltCoreHealthcheck::class,
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
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // Register service itself to container
        // Environment health checks
        $container->add(PhpVersionHealthcheck::class);
        $container->add(NextMinPhpVersionHealthcheck::class);
        $container->add(PcreHealthcheck::class);
        $container->add(MbstringHealthcheck::class);
        $container->add(IntlHealthcheck::class);
        $container->add(ImageHealthcheck::class);
        $container->add(TmpFolderWritableHealthcheck::class);
        $container->add(LogFolderWritableHealthcheck::class);
        // Config files health checks
        $container->add(AppConfigFileHealthcheck::class);
        $container->add(PassboltConfigFileHealthcheck::class);
        // Core health checks
        $container->add(CacheCoreHealthcheck::class);
        $container->add(DebugDisabledCoreHealthcheck::class);
        $container->add(SaltCoreHealthcheck::class);
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
        $container->add(GpgHomeVariableDefinedGpgHealthcheck::class);
        $container->add(GpgKeyNotDefaultGpgHealthcheck::class);
        $container->add(GpgPublicKeyReadableGpgHealthcheck::class);
        $container->add(GpgPrivateKeyReadableGpgHealthcheck::class);
        $container->add(GpgPrivateKeyFingerprintMatchGpgHealthcheck::class);
        $container->add(GpgPublicKeyInKeyringGpgHealthcheck::class);
        $container->add(GpgPublicKeyEmailGpgHealthcheck::class);
        // Application health checks
        $container->add(LatestVersionApplicationHealthcheck::class);
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
        // Database health checks
        $container->add(ConnectDatabaseHealthcheck::class);
        $container->add(TablesCountDatabaseHealthcheck::class);
        $container->add(DefaultContentDatabaseHealthcheck::class);
        $container->add(SchemaUpToDateDatabaseHealthcheck::class);

        // Append core health checks to service collector
        $container->add(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [PhpVersionHealthcheck::class])
            ->addMethodCall('addService', [NextMinPhpVersionHealthcheck::class])
            ->addMethodCall('addService', [PcreHealthcheck::class])
            ->addMethodCall('addService', [MbstringHealthcheck::class])
            ->addMethodCall('addService', [IntlHealthcheck::class])
            ->addMethodCall('addService', [ImageHealthcheck::class])
            ->addMethodCall('addService', [TmpFolderWritableHealthcheck::class])
            ->addMethodCall('addService', [LogFolderWritableHealthcheck::class])
            ->addMethodCall('addService', [AppConfigFileHealthcheck::class])
            ->addMethodCall('addService', [PassboltConfigFileHealthcheck::class])
            ->addMethodCall('addService', [CacheCoreHealthcheck::class])
            ->addMethodCall('addService', [DebugDisabledCoreHealthcheck::class])
            ->addMethodCall('addService', [SaltCoreHealthcheck::class])
            ->addMethodCall('addService', [FullBaseUrlCoreHealthcheck::class])
            ->addMethodCall('addService', [ValidFullBaseUrlCoreHealthcheck::class])
            ->addMethodCall('addService', [FullBaseUrlReachableCoreHealthcheck::class])
            ->addMethodCall('addService', [PeerValidSslHealthcheck::class])
            ->addMethodCall('addService', [HostValidSslHealthcheck::class])
            ->addMethodCall('addService', [NotSelfSignedSslHealthcheck::class])
            ->addMethodCall('addService', [PluginEnabledSmtpSettingsHealthcheck::class])
            ->addMethodCall('addService', [PluginEnabledJwtHealthcheck::class])
            ->addMethodCall('addService', [PhpGpgModuleInstalledGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgHomeVariableDefinedGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgKeyNotDefaultGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgPublicKeyReadableGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgPrivateKeyReadableGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgPrivateKeyFingerprintMatchGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgPublicKeyInKeyringGpgHealthcheck::class])
            ->addMethodCall('addService', [GpgPublicKeyEmailGpgHealthcheck::class])
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
            ->addMethodCall('addService', [ConnectDatabaseHealthcheck::class])
            ->addMethodCall('addService', [TablesCountDatabaseHealthcheck::class])
            ->addMethodCall('addService', [DefaultContentDatabaseHealthcheck::class])
            ->addMethodCall('addService', [SchemaUpToDateDatabaseHealthcheck::class]);
    }
}
