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
 * @since         5.5.0
 */

namespace Passbolt\Scim;

use App\Service\Command\GetUserCommandService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManagerInterface;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\TableRegistry;
use Cake\Routing\Middleware\RoutingMiddleware;
use Passbolt\Scim\Command\ScimSettingsCreateCommand;
use Passbolt\Scim\Command\ScimSettingsDeleteCommand;
use Passbolt\Scim\Event\ScimContainUserScimEntryListener;
use Passbolt\Scim\Middleware\ScimAuthMiddleware;
use Passbolt\Scim\Middleware\ScimLogMiddleware;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Service\Healthcheck\ScimHealthcheck;
use Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryHealthcheck;
use Passbolt\Scim\Utility\ScimConstants;

/**
 * ScimPlugin class
 */
class ScimPlugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        Configure::write('passbolt.plugins.scim.isInBeta', true);
        $this->attachListeners($app->getEventManager());
        $this->addScimAssociationToUsers();
        $this->addScimAssociationToScimUsers();
    }

    /**
     * Attach the Scim related event listeners.
     *
     * @param \Cake\Event\EventManagerInterface $eventManager EventManager
     * @return void
     */
    protected function attachListeners(EventManagerInterface $eventManager): void
    {
        $eventManager->on(new ScimContainUserScimEntryListener());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // SCIM Health checks
        $container->add(ScimHealthcheck::class);
        $container->add(ScimSecretTokenExpiryHealthcheck::class);
        // Add SCIM health check services to collector
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [ScimHealthcheck::class])
            ->addMethodCall('addService', [ScimSecretTokenExpiryHealthcheck::class]);
        $container
            ->extend(BodyParserMiddleware::class)
            ->addMethodCall('addParser', [
                    [ScimConstants::CONTENT_TYPE],
                    function ($body) {
                        if ($body === '') {
                            return [];
                        }
                        $decoded = json_decode($body, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            return (array)$decoded;
                        }

                        return null;
                    },
            ]);

        $container->add(ScimSettingsCreateCommand::class)->addArgument(GetUserCommandService::class);
        $container->add(ScimSettingsDeleteCommand::class)->addArgument(GetUserCommandService::class);
    }

    /**
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->insertAfter(RoutingMiddleware::class, ScimAuthMiddleware::class)
            ->insertAfter(BodyParserMiddleware::class, ScimLogMiddleware::class);

        return parent::middleware($middlewareQueue);
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $commands->add('passbolt scim_settings create', ScimSettingsCreateCommand::class);
            $commands->add('passbolt scim_settings delete', ScimSettingsDeleteCommand::class);
        }

        return $commands;
    }

    /**
     * @return void
     */
    protected function addScimAssociationToUsers(): void
    {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        if ($UsersTable->hasAssociation('ScimEntries')) {
            return;
        }
        $UsersTable->hasOne('ScimEntries', [
            'className' => 'Passbolt/Scim.ScimEntries',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'ScimEntries.foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function addScimAssociationToScimUsers(): void
    {
        $ScimUsersTable = TableRegistry::getTableLocator()->get('Passbolt/Scim.ScimUsers');
        if ($ScimUsersTable->hasAssociation('ScimEntries')) {
            return;
        }
        $ScimUsersTable->hasOne('ScimEntries', [
            'className' => 'Passbolt/Scim.ScimEntries',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'ScimEntries.foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            ],
        ]);
    }
}
