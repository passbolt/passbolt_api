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
 * @since         3.10.0
 */
namespace Passbolt\MfaPolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\MfaPolicies\Notification\Email\MfaPoliciesSettingsRedactorPool;
use Passbolt\MfaPolicies\Service\RememberAMonthSettingService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;

class MfaPoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Register email redactors
        $app->getEventManager()->on(new MfaPoliciesSettingsRedactorPool());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        /**
         * Overrides concrete implementation from MFA plugin.
         * **Note:** Only extend if MFA plugin enabled, otherwise this can throw errors.
         *
         * @see \Passbolt\MultiFactorAuthentication\Plugin::services()
         */
        if ($this->isFeaturePluginEnabled('MultiFactorAuthentication')) {
            $container
                ->extend(RememberAMonthSettingInterface::class)
                ->setConcrete(RememberAMonthSettingService::class);
        }
    }
}
