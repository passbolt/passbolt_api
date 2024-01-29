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
namespace Passbolt\PasswordExpiry;

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;
use Passbolt\PasswordExpiry\Event\PasswordExpiryResourceMarkedAsExpiredEventListener;
use Passbolt\PasswordExpiry\Notification\DigestTemplate\PasswordExpiryPasswordMarkedExpiredDigestTemplate;
use Passbolt\PasswordExpiry\Notification\Email\PasswordExpiryRedactorPool;
use Passbolt\PasswordExpiry\Notification\NotificationSettings\PasswordExpiryNotificationSettingsDefinition;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsServiceInterface;

class PasswordExpiryPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Register email redactors and listen to user disabling/deleting
        $app->getEventManager()
            ->on(new PasswordExpiryResourceMarkedAsExpiredEventListener())
            ->on(new PasswordExpiryNotificationSettingsDefinition())
            ->on(new PasswordExpiryRedactorPool());

        DigestTemplateRegistry::getInstance()->addTemplate(
            new PasswordExpiryPasswordMarkedExpiredDigestTemplate(),
        );
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(PasswordExpiryGetSettingsServiceInterface::class)
            ->setConcrete(PasswordExpiryGetSettingsService::class);
        $container
            ->add(PasswordExpirySetSettingsServiceInterface::class)
            ->setConcrete(PasswordExpirySetSettingsService::class);
        $container
            ->extend(PasswordExpiryValidationServiceInterface::class)
            ->setConcrete(PasswordExpiryValidationService::class)
            ->addArgument(PasswordExpiryGetSettingsServiceInterface::class);
        $container
            ->extend(ResourcesExpireResourcesServiceInterface::class)
            ->setConcrete(PasswordExpiryExpireResourcesService::class)
            ->addArgument(PasswordExpiryValidationServiceInterface::class);
    }
}
