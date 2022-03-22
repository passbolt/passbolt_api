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

use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\ServerRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetServiceInterface;  //phpcs:ignore
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetServiceInterface; //phpcs:ignore

/**
 * Class AccountRecoverySetServiceProvider
 *
 * @package Passbolt\AccountRecovery\ServiceProvider
 *
 * This provider set the default policy getter and setter
 * This allows other plugins to extend the policy with new feature in the future
 * such as shamir secret sharing
 */
class AccountRecoveryOrganizationPolicyServiceProvider extends ServiceProvider
{
    protected $provides = [
        AccountRecoveryOrganizationPolicySetServiceInterface::class,
        AccountRecoveryOrganizationPolicyGetServiceInterface::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(
                AccountRecoveryOrganizationPolicySetServiceInterface::class,
                AccountRecoveryOrganizationPolicySetService::class
            );

        $container
            ->add(
                AccountRecoveryOrganizationPolicyGetServiceInterface::class,
                AccountRecoveryOrganizationPolicyGetService::class
            )
            ->addArgument(ServerRequest::class);
    }
}
