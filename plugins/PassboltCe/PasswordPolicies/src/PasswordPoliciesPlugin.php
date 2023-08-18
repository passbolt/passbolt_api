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
namespace Passbolt\PasswordPolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsInterface;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsService;

class PasswordPoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    public const DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY = 'passbolt.plugins.passwordPolicies.defaultPasswordGenerator';

    public const DEFAULT_PASSWORD_GENERATOR_ENV_KEY =
        'PASSBOLT_PLUGINS_PASSWORD_POLICIES_DEFAULT_PASSWORD_GENERATOR_TYPE';

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(PasswordPoliciesGetSettingsInterface::class)
            ->setConcrete(PasswordPoliciesGetSettingsService::class);
    }
}
