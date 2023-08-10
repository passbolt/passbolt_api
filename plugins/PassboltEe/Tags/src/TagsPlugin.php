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
 * @since         3.7.0
 */
namespace Passbolt\Tags;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Passbolt\Tags\EventListener\AddTaggableBehaviorToTaggableTables;
use Passbolt\Tags\EventListener\GroupsUsersEventListener;

class TagsPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);
    }

    /**
     * Register Tags related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new AddTaggableBehaviorToTaggableTables()) // Decorate the core/other plugins table classes that can be tagged.
            ->on(new GroupsUsersEventListener()); // Remove tags when a group user is deleted and the user lost access to some resources.
    }
}
