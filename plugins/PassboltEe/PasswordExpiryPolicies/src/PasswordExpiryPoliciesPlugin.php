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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiryPolicies;

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsServiceInterface;
use Passbolt\PasswordExpiryPolicies\Command\PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand;
use Passbolt\PasswordExpiryPolicies\Notification\Email\PasswordExpiryPoliciesRedactorPool;
use Passbolt\PasswordExpiryPolicies\Notification\NotificationSettings\PasswordExpiryPoliciesNotificationSettingsDefinition; // phpcs:ignore
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesResourcesExpiryUpdateService;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesValidationService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesSetSettingsService;
use Psr\Container\ContainerInterface;

class PasswordExpiryPoliciesPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Register email redactors
        $app->getEventManager()
            ->on(new PasswordExpiryPoliciesRedactorPool())
            ->on(new PasswordExpiryPoliciesNotificationSettingsDefinition());
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        return $commands->add(
            'passbolt notify_about_expired_resources',
            PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand::class
        );
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->extend(PasswordExpiryGetSettingsServiceInterface::class)
            ->setConcrete(PasswordExpiryPoliciesGetSettingsService::class);
        $container
            ->extend(PasswordExpirySetSettingsServiceInterface::class)
            ->setConcrete(PasswordExpiryPoliciesSetSettingsService::class);
        $container
            ->add(PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService::class)
            ->addArgument(PasswordExpiryGetSettingsServiceInterface::class);
        $container
            ->add(PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand::class)
            ->addArgument(PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService::class);
        $container
            ->extend(PasswordExpiryValidationServiceInterface::class)
            ->setConcrete(PasswordExpiryPoliciesValidationService::class);
        $container
            ->add(PasswordExpiryPoliciesResourcesExpiryUpdateService::class)
            ->addArgument(PasswordExpiryValidationServiceInterface::class);
    }
}
