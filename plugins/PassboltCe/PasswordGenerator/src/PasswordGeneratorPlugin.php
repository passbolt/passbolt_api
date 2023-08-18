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
 * @since         3.3.0
 */
namespace Passbolt\PasswordGenerator;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;

class PasswordGeneratorPlugin extends BasePlugin
{
    public const DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY = 'passbolt.plugins.passwordGenerator.defaultPasswordGenerator';

    public const DEFAULT_PASSWORD_GENERATOR_ENV_KEY = 'PASSBOLT_PLUGINS_PASSWORD_GENERATOR_DEFAULT_GENERATOR';

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        Configure::load('Passbolt/PasswordGenerator.config', 'default', true);
    }
}
