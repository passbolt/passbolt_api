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
use Passbolt\MultiFactorAuthentication\Middleware\AppendProvidersToJwtChallengeMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaInjectFormMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRefreshTokenCreatedListenerMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\SetMfaSettingsInRequestMiddleware;
use Passbolt\MultiFactorAuthentication\Model\Behavior\IsMfaEnabledBehavior;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->addIsMfaEnabledBehaviorToUsersTable();
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue
            ->insertBefore(AuthenticationMiddleware::class, AppendProvidersToJwtChallengeMiddleware::class)
            ->insertAfter(AuthenticationMiddleware::class, SetMfaSettingsInRequestMiddleware::class)
            ->insertAfter(SetMfaSettingsInRequestMiddleware::class, MfaRequiredCheckMiddleware::class)
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
}
