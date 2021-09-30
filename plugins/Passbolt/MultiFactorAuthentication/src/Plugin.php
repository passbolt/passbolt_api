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
 * @since         3.1.0
 */
namespace Passbolt\MultiFactorAuthentication;

use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Event\AddIsMfaEnabledColumnToUsersGrid;
use Passbolt\MultiFactorAuthentication\Event\OnSuccessfulJwtLoginEventListener;
use Passbolt\MultiFactorAuthentication\Middleware\MfaInjectFormMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRefreshTokenCreatedListenerMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Model\Behavior\IsMfaEnabledBehavior;
use Passbolt\MultiFactorAuthentication\Notification\Email\MfaRedactorPool;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->addIsMfaEnabledBehaviorToUsersTable();
        $this->registerListeners($app);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue
            ->insertAfter(AuthenticationMiddleware::class, MfaRequiredCheckMiddleware::class)
            ->insertAfter(MfaRequiredCheckMiddleware::class, MfaInjectFormMiddleware::class)
            ->add(MfaRefreshTokenCreatedListenerMiddleware::class);
    }

    /**
     * @return void
     */
    public function addIsMfaEnabledBehaviorToUsersTable(): void
    {
        TableRegistry::getTableLocator()->get('Users')->addBehavior(IsMfaEnabledBehavior::class);
    }

    /**
     * Register MFA related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            // Decorate the users grid and add the column "is_mfa_enabled"
            ->on(new AddIsMfaEnabledColumnToUsersGrid()) // decorate the query to add the new property on the User entity
            ->on(new MfaRedactorPool()) // Register email redactors
            ->on(new OnSuccessfulJwtLoginEventListener());
    }
}
