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

use App\Service\Healthcheck\ConfigFiles\AppConfigFileHealthcheck;
use App\Service\Healthcheck\ConfigFiles\PassboltConfigFileHealthcheck;
use App\Service\Healthcheck\Core\CacheCoreHealthcheck;
use App\Service\Healthcheck\Core\DebugDisabledCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlReachableCoreHealthcheck;
use App\Service\Healthcheck\Core\SaltCoreHealthcheck;
use App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Environment\ImageHealthcheck;
use App\Service\Healthcheck\Environment\IntlHealthcheck;
use App\Service\Healthcheck\Environment\LogFolderWritableHealthcheck;
use App\Service\Healthcheck\Environment\MbstringHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PcreHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\TmpFolderWritableHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\Client;

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
    ];

    /**
     * TODO: Domain wise service provider - separate this method into different provider files
     *
     * {@inheritDoc}
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
        $container->add(FullBaseUrlReachableCoreHealthcheck::class)->addArgument('fullBaseUrlReachableClient');

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
            ->addMethodCall('addService', [FullBaseUrlReachableCoreHealthcheck::class]);
    }
}
