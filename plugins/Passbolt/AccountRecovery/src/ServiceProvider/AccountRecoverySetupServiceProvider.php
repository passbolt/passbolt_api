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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\ServiceProvider;

use App\Service\Setup\RecoverCompleteServiceInterface;
use App\Service\Setup\RecoverStartServiceInterface;
use App\Service\Setup\SetupCompleteServiceInterface;
use App\Service\Setup\SetupStartServiceInterface;
use App\ServiceProvider\SetupServiceProvider;
use Cake\Core\ContainerInterface;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoveryRecoverCompleteService;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoveryRecoverStartService;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoverySetupCompleteService;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoverySetupStartService;

/**
 * Class AccountRecoverySetupServiceProvider
 *
 * @package Passbolt\AccountRecovery\ServiceProvider
 *
 * This provider allows replacing the default recover and setup services
 * with services that extends the baseline with account recovery specific data and process
 */
class AccountRecoverySetupServiceProvider extends SetupServiceProvider
{
    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->extend(RecoverStartServiceInterface::class)
            ->setConcrete(AccountRecoveryRecoverStartService::class);
        $container
            ->extend(RecoverCompleteServiceInterface::class)
            ->setConcrete(AccountRecoveryRecoverCompleteService::class);
        $container
            ->extend(SetupStartServiceInterface::class)
            ->setConcrete(AccountRecoverySetupStartService::class);
        $container
            ->extend(SetupCompleteServiceInterface::class)
            ->setConcrete(AccountRecoverySetupCompleteService::class);
    }
}
