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
 * @since         4.2.0
 */
namespace Passbolt\PasswordPoliciesUpdate;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsInterface;
use Passbolt\PasswordPoliciesUpdate\Notification\Email\PasswordPoliciesSettingsRedactorPool;
use Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateGetSettingsService;

class PasswordPoliciesUpdatePlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Register email redactors
        $app->getEventManager()->on(new PasswordPoliciesSettingsRedactorPool());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        if ($container->has(PasswordPoliciesGetSettingsInterface::class)) {
            $container
                ->extend(PasswordPoliciesGetSettingsInterface::class)
                ->setConcrete(PasswordPoliciesUpdateGetSettingsService::class);
        } else {
            $container
                ->add(PasswordPoliciesGetSettingsInterface::class)
                ->setConcrete(PasswordPoliciesUpdateGetSettingsService::class);
        }
    }
}
