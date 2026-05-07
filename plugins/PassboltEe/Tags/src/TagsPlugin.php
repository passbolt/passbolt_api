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

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ToV5ServiceCollector;
use Passbolt\Tags\Command\Metadata\MigrateTagsCommand;
use Passbolt\Tags\EventListener\AddTaggableBehaviorToTaggableTables;
use Passbolt\Tags\EventListener\GroupsUsersEventListener;
use Passbolt\Tags\Service\Metadata\MigrateAllV4TagsToV5Service;

class TagsPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);

        if (Configure::read('passbolt.v5.enabled')) {
            MigrateAllV4ToV5ServiceCollector::add(MigrateAllV4TagsToV5Service::class);
        }
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

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands->add('passbolt metadata migrate_tags', MigrateTagsCommand::class);

        return $commands;
    }
}
