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
 * @since         3.5.0
 */

namespace App\ServiceProvider;

use App\Service\Setup\AbstractRecoverStartService;
use App\Service\Setup\AbstractSetupStartService;
use App\Service\Setup\DefaultRecoverStartService;
use App\Service\Setup\DefaultSetupStartService;
use App\Service\Setup\RecoverCompleteService;
use App\Service\Setup\RecoverCompleteServiceInterface;
use App\Service\Setup\RecoverStartUserInfoService;
use App\Service\Setup\SetupCompleteService;
use App\Service\Setup\SetupCompleteServiceInterface;
use App\Service\Setup\SetupStartUserInfoService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\ServerRequest;

class SetupServiceProvider extends ServiceProvider
{
    protected $provides = [
        AbstractSetupStartService::class,
        SetupStartUserInfoService::class,
        AbstractRecoverStartService::class,
        RecoverStartUserInfoService::class,
        SetupCompleteServiceInterface::class,
        RecoverCompleteServiceInterface::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // setup start
        $container
            ->add(AbstractSetupStartService::class, DefaultSetupStartService::class)
            ->addArgument(SetupStartUserInfoService::class);
        $container->add(SetupStartUserInfoService::class);
        // recover start
        $container
            ->add(AbstractRecoverStartService::class, DefaultRecoverStartService::class)
            ->addArgument(RecoverStartUserInfoService::class);
        $container->add(RecoverStartUserInfoService::class);
        // complete
        $container
            ->add(SetupCompleteServiceInterface::class, SetupCompleteService::class)
            ->addArgument(ServerRequest::class);
        $container
            ->add(RecoverCompleteServiceInterface::class, RecoverCompleteService::class)
            ->addArgument(ServerRequest::class);
    }
}
