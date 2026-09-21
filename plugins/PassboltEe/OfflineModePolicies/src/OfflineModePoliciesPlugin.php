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
 * @since         5.16.0
 */
namespace Passbolt\OfflineModePolicies;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Passbolt\OfflineMode\Form\OfflineSettingsFormInterface;
use Passbolt\OfflineModePolicies\Form\OfflineSettingsUpdateForm;

class OfflineModePoliciesPlugin extends BasePlugin
{
    /**
     * @param \Cake\Core\ContainerInterface $container Container.
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        if ($container->has(OfflineSettingsFormInterface::class)) {
            $container->extend(OfflineSettingsFormInterface::class)
                ->setConcrete(OfflineSettingsUpdateForm::class);
        } else {
            $container->add(OfflineSettingsFormInterface::class)
                ->setConcrete(OfflineSettingsUpdateForm::class);
        }
    }
}
